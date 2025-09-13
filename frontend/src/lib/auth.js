export const getToken = () => localStorage.getItem("token"); //lấy token từ localStorage
export const setToken = (token) => localStorage.setItem("token", token); //lưu token vào localStorage
export const removeToken = () => localStorage.removeItem("token"); //xóa token khỏi localStorage
export const isAuthenticated = () => !!getToken(); //kiểm tra xem có token hay không
