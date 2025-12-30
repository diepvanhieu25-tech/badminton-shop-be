<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Mail\SendOtpMail;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {

            $data['role'] = UserRole::CUSTOMER;

            $data['status'] = UserStatus::ACTIVE;

            $user = User::create($data);

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return ['user' => $user, 'token' => $token];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function forgotPassword(array $data)
    {
        $email = $data['email'];

        // 1. Tạo Key định danh cho email này (Ví dụ: otp_reset_nguyenvana@gmail.com)
        $cacheKey = 'otp_reset_' . $email;

        // 2. (Optional) Chặn spam: Kiểm tra xem vừa gửi chưa?
        if (Cache::has($cacheKey)) {
            throw new Exception('Mã OTP cũ vẫn còn hiệu lực. Vui lòng chờ 60s để gửi lại.');
        }
        
        // 3. Sinh OTP
        $otp = rand(100000, 999999);

        // 4. Lưu vào Cache: Key, Value, Thời gian (giây)
        // Sau 60s, Laravel tự động xóa key này đi.
        Cache::put($cacheKey, $otp, 60);

        // 5. Gửi Mail
        Mail::to($email)->send(new SendOtpMail($otp));

        return ['status' => true, 'message' => 'Mã OTP đã gửi. Hết hạn sau 60 giây.'];
    }

    /**
     * Xác thực OTP dùng Cache
     */
    public function resetPassword(array $data)
    {
        $email = $data['email'];
        $otpInput = $data['otp'];
        $newPassword = $data['password'];

        $cacheKey = 'otp_reset_' . $email;

        // 1. Lấy OTP từ Cache
        $cachedOtp = Cache::get($cacheKey);

        // 2. Kiểm tra logic
        // Nếu $cachedOtp là null => Tức là đã qua 60s, cache tự xóa rồi
        if (!$cachedOtp) {
            throw new Exception('Mã OTP đã hết hạn hoặc không tồn tại.');
        }

        // Kiểm tra khớp mã
        if ((string)$cachedOtp !== (string)$otpInput) {
            throw new Exception('Mã OTP không chính xác.');
        }

        // 3. Đổi mật khẩu
        $user = User::where('email', $email)->first();
        $user->password = $newPassword; // Model đã cast 'hashed'
        $user->save();

        // 4. Quan trọng: Xóa ngay OTP trong Cache để không dùng lại được lần 2
        Cache::forget($cacheKey);

        return ['status' => true, 'message' => 'Đổi mật khẩu thành công.'];
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        // 1. Kiểm tra User + Password
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return [
                'ok' => false,
                'status' => 401,
                'message' => 'Thông tin đăng nhập không chính xác.',
            ];
        }

        // 2. Kiểm tra trạng thái User
        if (($user->status ?? 'active') !== 'active') {
            return [
                'ok' => false,
                'status' => 403,
                'message' => 'Tài khoản của bạn đang bị khóa.',
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'ok' => true,
            'status' => 200,
            'user' => $user,
            'token' => $token,
        ];
    }

    public function loginWithSocial(array $data)
    {
        $provider = $data['provider'];
        $token = $data['access_token'];

        DB::beginTransaction();
        try {
            // 1. Lấy thông tin từ Google/Facebook
            // (Đoạn này vẫn dùng Socialite vì nó là logic xác thực bên thứ 3, ko phải DB)
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver($provider);
            $socialUser = $driver->stateless()->userFromToken($token);

            // 2. Tìm User dựa trên Social ID + Provider
            $user = $this->userRepository->findUserBySocialId($provider, $socialUser->getId());

            // 3. Nếu chưa từng login
            if (! $user) {
                // Thử tìm theo Email trước (tránh tạo trùng)
                $user = $this->userRepository->findByEmail($socialUser->getEmail());

                // Nếu vẫn không có -> Tạo User mới qua Repository
                if (! $user) {
                    $user = $this->userRepository->create([
                        'email' => $socialUser->getEmail(),
                        'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'status' => 'active',
                    ]);
                }

                // Tạo liên kết Social Account qua Repository
                $this->userRepository->createSocialAccount([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_user_id' => $socialUser->getId(),
                    'provider_email' => $socialUser->getEmail(),
                ]);
            }

            // 4. Kiểm tra khóa tài khoản (Logic nghiệp vụ)
            if (($user->status ?? 'active') !== 'active') {
                return ['ok' => false, 'status' => 403, 'message' => 'Tài khoản bị khóa.'];
            }

            // 5. Tạo Token
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return [
                'ok' => true,
                'status' => 200,
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Social Login Error: " . $e->getMessage());
            return ['ok' => false, 'status' => 401, 'message' => 'Lỗi xác thực Social.'];
        }
    }

    public function logout(User $user)
    {
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return true;
    }

    public function updateProfile(array $data)
    {
        $userId = Auth::user(); // Lấy user đang đăng nhập
        $user = User::findOrFail($userId);

        // 1. Xử lý Avatar (Nếu có gửi file ảnh lên)
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            // Xóa ảnh cũ nếu có (trừ ảnh mặc định nếu bạn có set)
            if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            // Lưu ảnh mới vào thư mục 'avatars' trong storage/app/public
            // Nó sẽ trả về đường dẫn: "avatars/ten_file_ngau_nhien.jpg"
            $path = $data['avatar']->store('avatars', 'public');
            
            // Cập nhật đường dẫn vào data để lưu DB
            $user->avatar_url = $path;
        }

        // 2. Xử lý Password (Nếu có gửi password mới)
        if (!empty($data['password'])) {
            // Model User của bạn đã có cast 'hashed' nên gán trực tiếp, không cần Hash::make
            $user->password = $data['password'];
        }

        // 3. Cập nhật các thông tin cơ bản khác (chỉ cập nhật nếu có dữ liệu)
        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }
        if (!empty($data['phone'])) {
            $user->phone = $data['phone'];
        }

        $user->save();

        return $user;
    }
}
