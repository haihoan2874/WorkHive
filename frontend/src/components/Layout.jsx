import React, { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import logo from "../assets/logo.png";
import { logout } from "../lib/api";

const Layout = ({ children }) => {
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const handleLogout = async () => {
    await logout();
    navigate("/"); // logout xong về trang chủ (tùy bạn muốn điều hướng đi đâu)
  };

  // class cho link
  const linkClass = ({ isActive }) =>
    `group flex items-center px-2 py-2 font-medium rounded-md transition-colors
     ${
       isActive
         ? "underline text-blue-600 font-semibold bg-gray-100"
         : "text-gray-600 hover:bg-gray-200 hover:text-gray-900 hover:underline"
     }`;
  const storedUser = (() => {
    try {
      return JSON.parse(localStorage.getItem("user") || "null");
    } catch {
      return null;
    }
  })();
  const displayName = storedUser?.name || storedUser?.email || "Guest";
  return (
    <div className="min-h-screen bg-gray-100">
      {/* Navbar */}
      <nav className="bg-white shadow border-b">
        <div className="flex items-center justify-between max-w-6xl mx-auto px-4 py-2 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between w-full">
            <NavLink
              to="/"
              className="flex items-center gap-3 text-gray-900 text-xl font-bold"
            >
              <img src={logo} alt="logo" className="h-8 w-8 rounded-full" />
              <span>WorkHive</span>
            </NavLink>

            {/* nút 3 gạch cho mobile */}
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

          {/* nút logout desktop */}
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

      <div className="flex">
        {/* overlay mobile */}
        {sidebarOpen && (
          <div
            onClick={() => setSidebarOpen(false)}
            className="fixed inset-0 z-40 md:hidden"
          >
            <div className="absolute inset-0 bg-gray-600 opacity-75"></div>
          </div>
        )}

        {/* Sidebar */}
        <aside
          className={`fixed inset-y-0 right-0 z-50 w-64 bg-white shadow-sm transition-transform duration-300 ease-in-out md:static md:h-screen md:translate-x-0 md:z-auto ${
            sidebarOpen ? "translate-x-0" : "translate-x-full"
          }`}
        >
          <nav className="mt-5 px-4">
            {/* close button mobile */}
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

            {storedUser && (
              <div className="px-3 py-3 mb-5 rounded-md bg-blue-200 shadow-sm text-gray-700 ">
                <div className="text-xl text-center">Welcome</div>
                <div className="font-bold text-lg text-center truncate uppercase">{displayName}</div>
              </div>
            )}

            {/* các link */}
            <NavLink
              to="/"
              className={linkClass}
              onClick={() => setSidebarOpen(false)}
            >
              📊 Dashboard
            </NavLink>
            <NavLink
              to="/projects"
              className={linkClass}
              onClick={() => setSidebarOpen(false)}
            >
              🤝 Projects
            </NavLink>
            <NavLink
              to="/posts"
              className={linkClass}
              onClick={() => setSidebarOpen(false)}
            >
              📝 Blog Posts
            </NavLink>

            {/* Logout mobile */}
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

        {/* Content */}
        <main className="flex-1 p-4 md:p-6">{children}</main>
      </div>
    </div>
  );
};

export default Layout;
