"use client";

import React from 'react';
import { AuthProvider } from '@/context/AuthContext';
import { CartProvider } from '@/context/CartContext';
import MiniCart from '@/components/MiniCart';

export default function Providers({ children }: { children: React.ReactNode }) {
  return (
    <AuthProvider>
      <CartProvider>
        {children}
        <MiniCart />
      </CartProvider>
    </AuthProvider>
  );
}
