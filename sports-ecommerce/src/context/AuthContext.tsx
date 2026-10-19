"use client";

import React, { createContext, useContext, useState, useEffect } from 'react';
import { ApiUser } from '@/types/api';
import * as api from '@/services/api';

interface AuthContextType {
  user: ApiUser | null;
  token: string | null;
  isLoading: boolean;
  login: (email: string, pass: string) => Promise<string>;
  register: (payload: { name: string; email: string; password: string; password_confirmation: string }) => Promise<void>;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<ApiUser | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    const savedToken = localStorage.getItem('badminton_token');
    if (savedToken) {
      setToken(savedToken);
      api.getProfile(savedToken)
        .then((userData) => setUser(userData))
        .catch(() => {
          localStorage.removeItem('badminton_token');
          setToken(null);
          setUser(null);
        })
        .finally(() => setIsLoading(false));
    } else {
      setIsLoading(false);
    }
  }, []);

  const login = async (email: string, pass: string): Promise<string> => {
    setIsLoading(true);
    try {
      const res = await api.login({ email, password: pass });
      localStorage.setItem('badminton_token', res.access_token);
      setToken(res.access_token);
      setUser(res.user);
      return res.access_token;
    } finally {
      setIsLoading(false);
    }
  };

  const register = async (payload: { name: string; email: string; password: string; password_confirmation: string }) => {
    setIsLoading(true);
    try {
      await api.register(payload);
      // Auto login after register
      await login(payload.email, payload.password);
    } finally {
      setIsLoading(false);
    }
  };

  const logout = () => {
    localStorage.removeItem('badminton_token');
    setToken(null);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
