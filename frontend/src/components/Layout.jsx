import React, { useState } from "react";
import { Link } from "react-router-dom";
import logo from "../assets/logo.png";
import { useNavigate } from "react-router-dom";
import { logout } from "../lib/api";

// Layout component
const Layout = ({ children }) => {
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Hàm xử lý Logout (tạm thời)
  const handleLogout = async () => {
    await logout();
  };

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Navbar - thanh điều hướng trên cùng */}
      <nav className="bg-white shadow border-b">
        <div className="flex items-center justify-between max-w-6xl mx-auto px-4 py-2 sm:px-6 lg:px-8">
          {/* Logo + mobile menu button*/}
          <div className="flex items-center justify-between w-full">
            <Link
              to="/"
              className="flex items-center gap-3 text-gray-900 text-xl font-bold"
            >
              <img src={logo} alt="logo" className="h-8 w-8 rounded-full" />
              <span className="">WorkHive</span>
            </Link>

            {/* icon 3 gach */}
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 mr-2"
            >
              <svg
                className="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
          </div>
          {/* nút đăng xuất bên phải */}
          <div className="hidden md:block">
            <button
              onClick={handleLogout}
              className="px-3 py-2 font-medium rounded-md text-red-600 hover:bg-red-600 hover:text-white transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </nav>

      {/* Main layout - Chia làm 2 phần: sidebar + content */}
      <div className="flex ">
        {/* Mobile sidebar overlay */}
        {sidebarOpen && (
          <div
            onClick={() => setSidebarOpen(false)}
            className="fixed inset-0 z-40 md:hidden"
          >
            <div className="absolute inset-0 bg-gray-600 opacity-75"></div>
          </div>
        )}
        {/* Sidebar - Menu bên trái */}
        <aside
          className={`fixed inset-y-0 right-0 z-50 w-64 bg-white shadow-sm transition-transform duration-300 ease-in-out md:static md:h-screen md:translate-x-0 md:z-auto ${
            sidebarOpen ? "translate-x-0" : "translate-x-full"
          }`}
        >
          {/* Sidebar content */}
          <nav className="mt-5 px-4 ">
            {/* Close button */}
            <div className="md:hidden flex justify-start p-2 ">
              <button
                onClick={() => setSidebarOpen(false)}
                className="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
              >
                <svg
                  className="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>

            {/* Dashboard link */}
            <Link
              to="/"
              onClick={() => setSidebarOpen(false)}
              className="group flex items-center px-2 py-2 font-medium rounded-md text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors"
            >
              📊 Dashboard
            </Link>
            {/* Project Link */}
            <Link
              to="/projects"
              onClick={() => setSidebarOpen(false)}
              className="group flex items-center px-2 py-2 font-medium rounded-md text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors"
            >
              🤝 Projects
            </Link>
            {/* Posts Link */}
            <Link
              to="/posts"
              onClick={() => setSidebarOpen(false)}
              className="group flex items-center px-2 py-2 font-medium rounded-md text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors"
            >
              📝 Blog Posts
            </Link>

            {/* Mobile Logout button */}

            <div className="md:hidden mt-4 pt-4 border-t border-gray-200">
              <button
                onClick={() => {
                  handleLogout();
                  setSidebarOpen(false);
                }}
                className="w-full px-2 py-5 font-medium rounded-md text-red-600 hover:bg-gray-200 hover:text-red-800 transition-colors"
              >
                Logout
              </button>
            </div>
          </nav>
        </aside>

        {/* Main Content - khu vực hiển thị nội dung */}
        <main className="flex-1 p-4 md:p-6">
          {/* render nội dung được truyền vào */}
          {children}
        </main>
      </div>
    </div>
  );
};

export default Layout;
