"use client";

import React, { createContext, useContext, useState, useEffect, useCallback, useRef } from 'react';
import { ApiCart, ApiCartItem } from '@/types/api';
import * as api from '@/services/api';
import { useAuth } from './AuthContext';

interface AddItemMeta {
  name?: string;
  price?: number;
  sku?: string;
  image_url?: string | null;
  attributes?: Record<string, string> | { code: string; name: string; value: string }[];
}

interface CartContextType {
  cart: ApiCart | null;
  cartCount: number;
  isLoading: boolean;
  isMiniCartOpen: boolean;
  openMiniCart: () => void;
  closeMiniCart: () => void;
  toggleMiniCart: () => void;
  addItem: (variantId: number, quantity?: number, meta?: AddItemMeta) => Promise<void>;
  updateQty: (itemId: number, quantity: number) => Promise<void>;
  removeItem: (itemId: number) => Promise<void>;
  refreshCart: (customToken?: string) => Promise<void>;
}

const GUEST_CART_STORAGE_KEY = 'badminton_guest_cart';

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { token } = useAuth();
  const [cart, setCart] = useState<ApiCart | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [isMiniCartOpen, setIsMiniCartOpen] = useState<boolean>(false);
  const isSyncingGuestRef = useRef(false);

  const openMiniCart = useCallback(() => setIsMiniCartOpen(true), []);
  const closeMiniCart = useCallback(() => setIsMiniCartOpen(false), []);
  const toggleMiniCart = useCallback(() => setIsMiniCartOpen((prev) => !prev), []);

  // Helper to get local guest cart
  const getGuestCart = (): ApiCart => {
    if (typeof window === 'undefined') {
      return { id: null, total_items: 0, total_qty: 0, total_price: 0, items: [] };
    }
    try {
      const raw = localStorage.getItem(GUEST_CART_STORAGE_KEY);
      if (!raw) return { id: null, total_items: 0, total_qty: 0, total_price: 0, items: [] };
      const items: ApiCartItem[] = JSON.parse(raw);
      const total_qty = items.reduce((sum, item) => sum + item.quantity, 0);
      const total_price = items.reduce((sum, item) => sum + item.subtotal, 0);
      return {
        id: null,
        total_items: items.length,
        total_qty,
        total_price,
        items,
      };
    } catch {
      return { id: null, total_items: 0, total_qty: 0, total_price: 0, items: [] };
    }
  };

  const saveGuestCart = (items: ApiCartItem[]) => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(GUEST_CART_STORAGE_KEY, JSON.stringify(items));
    }
  };

  const refreshCart = useCallback(async (customToken?: string) => {
    setIsLoading(true);
    const activeToken = customToken || token;
    try {
      if (activeToken) {
        // Sync any guest items to backend first before fetching
        const guestCart = getGuestCart();
        if (guestCart.items.length > 0 && !isSyncingGuestRef.current) {
          isSyncingGuestRef.current = true;
          try {
            for (const item of guestCart.items) {
              if (item.product_variant_id) {
                await api.addToCart(item.product_variant_id, item.quantity, activeToken);
              }
            }
            if (typeof window !== 'undefined') {
              localStorage.removeItem(GUEST_CART_STORAGE_KEY);
            }
          } catch (e) {
            console.error("Failed to sync guest cart items on refreshCart", e);
          } finally {
            isSyncingGuestRef.current = false;
          }
        }

        const cartData = await api.getCart(activeToken);
        setCart(cartData);
      } else {
        setCart(getGuestCart());
      }
    } catch {
      if (!activeToken) {
        setCart(getGuestCart());
      } else {
        setCart({
          id: null,
          total_items: 0,
          total_qty: 0,
          total_price: 0,
          items: [],
        });
      }
    } finally {
      setIsLoading(false);
    }
  }, [token]);

  // Sync guest cart to backend upon login
  useEffect(() => {
    async function syncGuestCartOnLogin() {
      if (!token || isSyncingGuestRef.current) return;
      
      const guestCart = getGuestCart();
      if (guestCart.items.length > 0) {
        isSyncingGuestRef.current = true;
        try {
          for (const item of guestCart.items) {
            await api.addToCart(item.product_variant_id, item.quantity, token);
          }
          if (typeof window !== 'undefined') {
            localStorage.removeItem(GUEST_CART_STORAGE_KEY);
          }
        } catch (e) {
          console.error("Failed to sync guest cart items on login", e);
        } finally {
          isSyncingGuestRef.current = false;
        }
      }
      refreshCart();
    }

    syncGuestCartOnLogin();
  }, [token, refreshCart]);

  const addItem = async (variantId: number, quantity: number = 1, meta?: AddItemMeta) => {
    setIsLoading(true);
    try {
      if (token) {
        await api.addToCart(variantId, quantity, token);
        await refreshCart();
      } else {
        // Handle guest cart in localStorage
        const currentGuest = getGuestCart();
        const existingIdx = currentGuest.items.findIndex(i => i.product_variant_id === variantId);

        let newItems: ApiCartItem[];
        if (existingIdx >= 0) {
          const existing = currentGuest.items[existingIdx];
          const newQty = existing.quantity + quantity;
          const updatedItem: ApiCartItem = {
            ...existing,
            quantity: newQty,
            subtotal: newQty * existing.price,
          };
          newItems = [...currentGuest.items];
          newItems[existingIdx] = updatedItem;
        } else {
          const unitPrice = meta?.price || 0;
          const newItem: ApiCartItem = {
            id: Date.now(),
            quantity,
            product_variant_id: variantId,
            sku: meta?.sku || `VAR-${variantId}`,
            name: meta?.name || 'Sản phẩm cầu lông',
            image_url: meta?.image_url || null,
            attributes: meta?.attributes,
            price: unitPrice,
            subtotal: unitPrice * quantity,
            in_stock: true,
            max_qty: 99,
            is_selected: true,
          };
          newItems = [...currentGuest.items, newItem];
        }

        saveGuestCart(newItems);
        const total_qty = newItems.reduce((sum, item) => sum + item.quantity, 0);
        const total_price = newItems.reduce((sum, item) => sum + item.subtotal, 0);
        setCart({
          id: null,
          total_items: newItems.length,
          total_qty,
          total_price,
          items: newItems,
        });
      }
      setIsMiniCartOpen(true);
    } finally {
      setIsLoading(false);
    }
  };

  const updateQty = async (itemId: number, quantity: number) => {
    setIsLoading(true);
    try {
      if (token) {
        await api.updateCartItem(itemId, quantity, token);
        await refreshCart();
      } else {
        const currentGuest = getGuestCart();
        const newItems = currentGuest.items.map(item => {
          if (item.id === itemId) {
            return {
              ...item,
              quantity,
              subtotal: quantity * item.price,
            };
          }
          return item;
        }).filter(item => item.quantity > 0);

        saveGuestCart(newItems);
        const total_qty = newItems.reduce((sum, item) => sum + item.quantity, 0);
        const total_price = newItems.reduce((sum, item) => sum + item.subtotal, 0);
        setCart({
          id: null,
          total_items: newItems.length,
          total_qty,
          total_price,
          items: newItems,
        });
      }
    } finally {
      setIsLoading(false);
    }
  };

  const removeItem = async (itemId: number) => {
    setIsLoading(true);
    try {
      if (token) {
        await api.removeCartItem(itemId, token);
        await refreshCart();
      } else {
        const currentGuest = getGuestCart();
        const newItems = currentGuest.items.filter(item => item.id !== itemId);
        saveGuestCart(newItems);
        const total_qty = newItems.reduce((sum, item) => sum + item.quantity, 0);
        const total_price = newItems.reduce((sum, item) => sum + item.subtotal, 0);
        setCart({
          id: null,
          total_items: newItems.length,
          total_qty,
          total_price,
          items: newItems,
        });
      }
    } finally {
      setIsLoading(false);
    }
  };

  const cartCount = cart?.total_qty || cart?.total_items || 0;

  return (
    <CartContext.Provider
      value={{
        cart,
        cartCount,
        isLoading,
        isMiniCartOpen,
        openMiniCart,
        closeMiniCart,
        toggleMiniCart,
        addItem,
        updateQty,
        removeItem,
        refreshCart,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
};
