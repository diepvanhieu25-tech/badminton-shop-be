
export interface Product {
  id: string;
  name: string;
  brand: string;
  price: number;
  image: string;
  tag?: string;
  discount?: number;
  category: 'badminton' | 'pickleball' | 'tennis';
}

export interface CategoryItem {
  id: string;
  title: string;
  subtitle: string;
  image: string;
  badge?: string;
}

export interface NewsArticle {
  id: string;
  title: string;
  summary: string;
  date: string;
  image: string;
  tag?: string;
}

export interface ChatMessage {
  role: 'user' | 'model';
  text: string;
}
