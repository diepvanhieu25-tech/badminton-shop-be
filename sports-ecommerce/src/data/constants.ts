
import { Product, CategoryItem, NewsArticle } from '@/types/types';

export const PRODUCTS: Product[] = [
  {
    id: 'p1',
    brand: 'Yonex',
    name: 'Vợt Cầu Lông Yonex Astrox 77 Pro - High Precision',
    price: 3450000,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB8gleL9bWcClbFZEMbsUdbyBgDHIzqF0V3truvTOfB2WPhS0g3xNdxRafJ5zPPodIeYsIJg3ZHH82T1PBiTqrSc6zWZcq0-xC1xIvnAdB4ZQuQ4uFhD7C1Kb4SLl2HX7hPysKwEUBb16bFh0I6Jy8CI7wjeIb_6RoO_5IJ5Akhcn40qwa1shN2F8o3sq-Dzg5vYvPww2izibvHJqeesWFej19PKfgP7wy1BULuPLPqs2I9VByJG-lXtywL-jjOjexzUqxiPwVx5Q',
    tag: 'New Arrival',
    category: 'badminton'
  },
  {
    id: 'p2',
    brand: 'Lining',
    name: 'Giày Cầu Lông Lining AYAT003-2 Chuyên Nghiệp',
    price: 1850000,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZcJhdYBL1HdpIS7wgnNmRPLHyBnxxCZ3zrOorHz7VZRd9sT_14XKddbCCyzLLT1-DjGrxACLzUeaXGwPGMej8S0XR16AY6Jf78NOoKm-831XQtysX9TL5DiptK5aaZwbLs1NRolnDa__1MeARk7CowTmsmwC7RjNIxEamgvMqpDkgFUepATVrZZpPod_Fe5aBi7JzufPMbuPB25fDpkixnVDM0PTH7qcsemhRVB-98K2QA9crY0crGqMrboy-lkikfMsaRV97Dw',
    tag: 'Best Seller',
    category: 'badminton'
  },
  {
    id: 'p3',
    brand: 'Vina Star',
    name: 'Ống Cầu Lông Vina Star (12 Quả) - Tiêu Chuẩn Thi Đấu',
    price: 180000,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBO-ZdgBDEgMa3J1hgEY-FlqZHCg2AbosBgTN30iQcMgb8u_l6TJBlVpvZAJJJYWtQjK-1ykdSDJ0UjlJhXHUzCINkVUUfhy1n1CZwcOPU-K_U3KRwiD66b43tsCOXrSchkmtE1PIxPSHzQp2b6xS3D7pCbl1EP0-K0inY4ARogyDfCH911bLFb2e8maYFcx1yVBkr71xcBw6TlSugC56w4DUCe7MOdQhy-pJ0sOPBTFp9z5Sgu_jfkqReayj2oij3Ay6sh84Rl-Q',
    category: 'badminton'
  },
  {
    id: 'p4',
    brand: 'Wilson',
    name: 'Vợt Tennis Wilson Clash 100 V2 - Kiểm Soát Tốt',
    price: 4200000,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCUghx0UTx3i3Iga6zTo3tUF_Fxicvla_Q5DRV4OfE44ThLlTx1DCjsJDq6aFmY-xqrDCnN4G5bs-IGivwLlE-gTjGROwIHyGrNQkP97S1GnrYeKE1pahpWx1lqH66GDnKy8UUFBGDDH2PnMeKqzhz5mzVla_ksSqCNLZR3ZcuLy_Z-VYgjUzJKMjJtH-8z3q0_Agb7ZELPElru120RYTfLfjgfRuFAnnlPgq9oSxTrONKEGOanWbYyZ2NEgR0TbijdESfGulNyng',
    discount: 20,
    category: 'tennis'
  }
];

export const CATEGORIES_BADMINTON: CategoryItem[] = [
  { id: 'b1', title: 'Vợt Cao Cấp', subtitle: 'Công nghệ Nhật Bản', image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDAECIautILpnJvuFKuCRKgXzCBQETa_2ISz10Cgrfy_AZDyt3K4OLHxKcg96EAG2GqxUdFX-PhpzwhThIjUQvnn1wrOWeTG9i29ajENKrk26Jd-V-oX4L9Htvc24yd-lC4BeDvmdbXSCukZkfGitn_MugJwRrK3RLMp-dboWxK4Yq8LDTGF5iRSYM8hD64TF5HEv4ydm2zDXX8eLaT7bPmjadgV4DIXGpSlr5W7DtwqYet_K2H1chTprnNXX-S0rVt9GFGueQDQA', badge: 'Vợt Yonex' },
  { id: 'b2', title: 'Giày Thi Đấu', subtitle: 'Bám sân cực tốt', image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBtrU_B1XxAbWwyEIeQCec6GRR7tIq-v46B8PVECT0OSKfn45yi-DeP1z4IH_WJZnjzMztcZM263hHQ1aFwvp3erscHpTSaGz0nNlABRl5qc6wXda4YLSudocd93jkAWKj8mRxJ6HLo2bPxDiVgXBIQZnSQJupzo3o5PgEaCC4SKcnQhwygYIWy1QASrkl548I2Kr5pLceTUzHJF3APjlmIQbjSWlOj5y1i_0DR-RdigxPS8YxZiKKjGMckrkgHkFyAVR6553r4eg', badge: 'Giày Victor' },
  { id: 'b3', title: 'Trang Phục', subtitle: 'Thoáng khí, co giãn', image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBWyv93UJrJcnxxGWsEysrBJxS8IHPorPXU6iE8rOxqdvxBGf54N8yI87zE7NIVFkYAzOlfCtJ68xo6Y5Fb057SdMQVLRP1gWPM455Woj4zlLfLczuGR3j57EshEK0igcquBgH5U9UNcMPVthD3xCOuZ7sXqFXzdouV5_cMiNnGhz8qAkIIoNGnMTtYCkM8yDo36Dl-3CsVKXHBZwYMOOqty-1R7LMrfAyHrZ5FKeKfhYemk3wZ7emdHqJaOKoaxbcWkQwdbQlJQw', badge: 'Quần Áo' },
  { id: 'b4', title: 'Phụ Kiện Cầu', subtitle: 'Độ bền cao', image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVNAvyUM7OsKdkYV4cggdK5IHSTOkk_xkmEMuNXkp350KftSrxzJ9leN4_eA1rf2P1QXkhXwFSIYMWvcrnkGcYGDJTLZ6ORYdDaMlLeAqbG4iQo8noKoufWoyfPVt2JDwLVB1gNkxsavErCI16k4XBiearF2LWnwrXGztV2Me0o2mbj-fVle_f2v4tgoll7OV_wbWDuAKCT85vRV3SjDokMOd3YYqQ9OkvM6VDCoAQV8CXEtbyL2RUYEZEhxZTH1tDaknrkW7wlQ', badge: 'Quả Cầu' }
];

export const NEWS: NewsArticle[] = [
  {
    id: 'n1',
    title: 'Giải cầu lông Yonex mở rộng 2024: Những điều cần biết',
    summary: 'Tổng hợp thông tin mới nhất về giải đấu lớn nhất trong năm...',
    date: '24/05/2024',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCWEtKBgKlePwV_Wqc34l_GHTP3EWczu8ZNk2uj8RRuQa4UEWe_mX0_6tjx1qw8HHcBbuxeXeTLHJBuZxkGouwYyrwMVO7ZmvR3Y3AfinhSRftCVEqZv4d2FmOCff42ApoTE_OBOaWRzOCQq7BRSM1ORby5iuKU4vJhzEPMLgViMfS0TV9SdGJ3s34KSDltkh1J89JUOknwj6KeeqkBnITCNMFw6pqkPmz_WNgF71SGzQKOKRcrZ3fE3C6MNF72fBuiO2EllALccQ',
    tag: 'HOT NEWS'
  },
  {
    id: 'n2',
    title: 'Tại sao Pickleball đang trở thành cơn sốt tại Việt Nam?',
    summary: 'Môn thể thao mới lạ này đang thu hút hàng ngàn người tham gia...',
    date: '22/05/2024',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB4tk2XMF5_cmexTapRvvdSoSjB5H3L5VJcChq7ImiTigs6sf2YMhyO2ECqjXRp5OE1F5TwVhiiOHfXEWTlCEE9I5ttjHNDLAgENxgzzb_XRLCZiKo-UwsMIyg6d1lb7qiLVt7GKcq6y8cGkBwwAahsIsy8bKx8l2nNUCizyHUQwHqmKPeFTzXvFtqaDv7iY22BQeTiOZIAlc8_lVXEcqXY2VMWYW578cf0niudLkoY9GuAdV-U45-jocI_carMbVBmUbmb4MagTg'
  }
];
