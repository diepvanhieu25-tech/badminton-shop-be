<x-mail::message>
# Xin chào!

Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu.

<x-mail::button :url="$url">
Đặt lại mật khẩu
</x-mail::button>

Nếu bạn không yêu cầu, vui lòng bỏ qua email này.

Thân ái,<br>
{{ config('app.name') }}
</x-mail::message>