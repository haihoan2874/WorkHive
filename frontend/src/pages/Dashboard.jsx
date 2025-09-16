import React, { useEffect, useState } from "react";
import api from "../lib/api";

const Dashboard = () => {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    let isMounted = true;
    setLoading(true);

    api
      .get("/projects")
      .then((res) => {
        const { projects } = res.data?.data || { projects: [] };
        setProjects(Array.isArray(projects) ? projects : []);
      })
      .catch((err) => {
        if (!isMounted) return;
        setError(err?.response?.data?.message || "Không tải được projects");
      })
      .finally(() => {
        if (!isMounted) return;
        setLoading(false);
      });

    return () => {
      isMounted = false;
    };
  }, []);

  if (loading) {
    return <div className="p-6 text-lg animate-pulse">⏳ Đang tải...</div>;
  }

  if (error) {
    return <div className="p-6 text-red-600 font-medium">{error}</div>;
  }

  const normalizeStatus = (s) => {
    const v = String(s || "").toLowerCase();
    if (v === "in-progress" || v === "in_progress") return "in_progress";
    if (v === "done" || v === "completed") return "completed";
    if (v === "pending") return "pending";
    return v;
  };
  const statusClasses = (s) => {
    switch (normalizeStatus(s)) {
      case "completed":
        return "bg-green-100 text-green-700 ring-1 ring-green-200";
      case "in_progress":
        return "bg-blue-100 text-blue-700 ring-1 ring-blue-200";
      case "pending":
        return "bg-amber-100 text-amber-700 ring-1 ring-amber-200";
      default:
        return "bg-gray-100 text-gray-700 ring-1 ring-gray-200";
    }
  };

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold tracking-tight text-gray-800">
        📂 Danh sách Projects
      </h1>

      {projects.length === 0 ? (
        <div className="text-gray-500 italic">Chưa có project nào.</div>
      ) : (
        <ul className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
          {projects.map((p) => (
            <li
              key={p.id}
              className="bg-white shadow-md rounded-2xl p-5 hover:shadow-xl transition-shadow duration-300 border border-gray-100"
            >
              <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold text-gray-800">
                  {p.title}
                </h2>
                {p.status && (
                  <span
                    className={`px-3 py-1 text-xs font-medium rounded-full ${statusClasses(
                      p.status
                    )}`}
                  >
                    {normalizeStatus(p.status)}
                  </span>
                )}
              </div>

              {p.deadline && (
                <p className="mt-2 text-sm text-gray-500">
                  ⏰ Deadline: <span className="font-medium">{p.deadline}</span>
                </p>
              )}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default Dashboard;
