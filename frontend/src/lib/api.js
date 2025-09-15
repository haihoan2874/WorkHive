import axios from "axios";

const API_URL = import.meta.env.VITE_API_URL || "http://127.0.0.1:8000/api";

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

//phần thêm token vào header
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

//phần xử lý lỗi
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem("token");
      window.location.href = "/login";
    }
    return Promise.reject(error);
  }
);

export const login = async (email, password) => {
  try {
    const response = await api.post("/auth/login", { email, password });
    const { token, user } = response.data?.data || {};
    if (token) localStorage.setItem("token", token);
    return { success: true, user, token };
  } catch (error) {
    return {
      success: false,
      error: error?.response?.data?.message || "Đăng nhập thất bại",
    };
  }
};

export const register = async (name, email, password) => {
  try {
    const response = await api.post("/auth/register", {
      name,
      email,
      password,
      password_confirmation: password,
    });
    const { token, user } = response.data?.data || {};
    if (token) localStorage.setItem("token", token);
    return { success: true, user, token };
  } catch (error) {
    return {
      success: false,
      error: error?.response?.data?.message || "Đăng ký thất bại",
    };
  }
};

export const logout = async () => {
  try {
    await api.post("/auth/logout");
  } catch (error) {
    console.log("Logout failed: ", error);
  } finally {
    localStorage.removeItem("token");
    window.location.href = "/login";
  }
};

export default api;
