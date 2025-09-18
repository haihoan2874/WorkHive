import React, { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { getProject, getProjectTasks } from "../lib/api";

const ProjectDetails = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  // State quản lý data
  const [project, setProject] = useState(null);
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Gọi API khi component mount
  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);

        // Gọi API song song để lấy project và tasks
        const [projectResult, tasksResult] = await Promise.all([
          getProject(id),
          getProjectTasks(id),
        ]);

        // Xử lý kết quả project
        if (projectResult.success) {
          setProject(projectResult.data?.data?.project || null);
        } else {
          setError(projectResult.error);
          return;
        }

        // Xử lý kết quả tasks
        if (tasksResult.success) {
          const arr = tasksResult.data?.data;
          setTasks(Array.isArray(arr) ? arr : []);
        } else {
          setError(tasksResult.error);
        }
      } catch (error) {
        setError("Có lỗi xảy ra khi tải dữ liệu");
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [id]);

  // Loading state
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <div className="mt-4 text-xl text-gray-600">Đang tải...</div>
        </div>
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="text-red-500 text-xl mb-4">{error}</div>
          <button
            onClick={() => navigate("/projects")}
            className="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
          >
            Quay lại danh sách
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-6xl mx-auto p-6">
      {/* Header với nút quay lại */}
      <div className="mb-6">
        <button
          onClick={() => navigate("/projects")}
          className="text-indigo-600 hover:text-indigo-800 mb-4"
        >
          ← Quay lại danh sách projects
        </button>
        <h1 className="text-2xl font-bold">{project?.title}</h1>
        <p className="text-gray-600">{project?.description}</p>
      </div>

      {/* Thông tin project */}
      <div className="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 className="text-xl font-semibold mb-4">Thông tin Project</h2>
        <div>
          <div className="mb-2">
            <span className="font-medium text-gray-700">Trạng thái: </span>
            <span
              className={`ml-2 px-2 py-1 rounded text-sm ${
                project?.status === "active"
                  ? "bg-green-500 text-white"
                  : "bg-red-500 text-white"
              }`}
            >
              {project?.status === "active" ? "Đang hoạt động" : "Tạm ngừng"}
            </span>
          </div>
          <div>
            <span className="font-medium text-gray-700">Deadline: </span>
            <span className="ml-2 text-gray-600">
              {project?.deadline
                ? new Date(project.deadline).toLocaleDateString("vi-VN")
                : "Chưa có deadline"}
            </span>
          </div>
        </div>
      </div>

      {/* Danh sách tasks */}
      <div className="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 className="text-xl font-semibold mb-4">
          Danh sách Tasks ({Array.isArray(tasks) ? tasks.length : 0})
        </h2>

        {Array.isArray(tasks) && tasks.length > 0 ? (
          tasks.map((task) => (
            <div
              key={task.id || task.title}
              className="border rounded p-4 mb-3 shadow-sm bg-gray-50"
            >
              <h3 className="font-semibold text-lg">{task.title}</h3>
              <p className="text-gray-600">{task.description}</p>
              <div className="mt-2 text-sm text-gray-500">
                <p>
                  <span className="font-medium">Deadline:</span>{" "}
                  {task.due_date
                    ? new Date(task.due_date).toLocaleDateString("vi-VN")
                    : "Chưa có"}
                </p>
                <p>
                  <span className="font-medium">Assignee:</span>{" "}
                  {task.assignee_id || "Chưa giao"}
                </p>
                <p>
                  <span className="font-medium">Trạng thái:</span>{" "}
                  {task.status === "completed" ? "✅ Hoàn thành" : "⌛ Đang làm"}
                </p>
              </div>
            </div>
          ))
        ) : (
          <p className="text-gray-500 text-center py-8">Chưa có task nào</p>
        )}
      </div>
    </div>
  );
};

export default ProjectDetails;
