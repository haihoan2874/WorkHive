# WorkHive — Lộ trình 4 tuần (Laravel + React)

Mục tiêu: Xây dựng một Task & Blog Platform (API bằng Laravel, frontend bằng React + Tailwind). Thời lượng đề xuất: 30–60 phút/ngày.

## Tổng quan lộ trình (chi tiết hàng ngày)

Tuần 1 — Backend (Laravel API)

- (Xong)Ngày 1 — Khởi tạo & DB
  - Tạo project: composer create-project laravel/laravel workhive
  - Tạo .env, cấu hình DB MySQL, php artisan key:generate
  - Tạo migration users (name, email, password, role)
  - Chạy: composer install, php artisan migrate
- (Xong)Ngày 2 — Auth với Sanctum
  - Cài: composer require laravel/sanctum
  - Publish config & migrate
  - Tạo AuthController: register/login/logout (token-based)
  - Test API bằng Postman (Register → Login → Bearer token)
- (Xong)Ngày 3 — Projects
  - Migration projects (id, title, description, owner_id, status, deadline)
  - Model Project + policy (owner only edit)
  - Controller ProjectController: index, show, store, update, destroy
  - Routes API: /api/projects
    -(Xong) Ngày 4 — Tasks (liên kết Project)
  - Migration tasks (id, project_id, title, description, due_date, status, assignee_id)
  - Quan hệ: Project hasMany Tasks; Task belongsTo Project
  - TaskController CRUD; route /api/projects/{project}/tasks hoặc /api/tasks
- (Xong) Ngày 5 — Posts (Blog)
  - Migration posts (id, user_id, title, body, published_at, slug)
  - Controller PostController CRUD; route /api/posts
- (Xong) Ngày 6 — Comments (liên kết Post)
  - Migration comments (id, post_id, user_id, body)
  - CommentController CRUD; route /api/posts/{post}/comments
- (Xong)Ngày 7 — Review
  - Viết API Resource/Transformer
  - Thêm validation, policies, error handling
  - Tạo seeders & factory dữ liệu mẫu
  - Viết docs ngắn API.md hoặc Postman collection

Tuần 2 — Frontend cơ bản (React + Vite + Tailwind)

-(Xong) Ngày 8 — Setup UI
  - pnpm/npm create vite@latest workhive-frontend --template react
  - Cài tailwind, cấu hình, tạo layout (Navbar, Sidebar)
- Ngày 9 — Auth UI
  - Forms Register/Login, call /api/register & /api/login
  - Lưu token vào localStorage; tạo helper fetch với Authorization header
- Ngày 10 — Dashboard
  - Page: /dashboard — fetch /api/projects, show cards
- Ngày 11 — Project Detail
  - Page: /projects/:id — fetch project + tasks, list tasks
- Ngày 12 — Blog list
  - Page: /posts — fetch /api/posts
- Ngày 13 — Blog detail + comments
  - Page: /posts/:slug — show post, fetch/post comment
- Ngày 14 — Review & fix UX bugs

Tuần 3 — CRUD + UI nâng cao

- Ngày 15–16: Forms thêm/sửa Project & Task (validate client/server)
- Ngày 17: Update trạng thái task (toggle done/pending)
- Ngày 18–19: Thêm Post & Comment từ UI
- Ngày 20: Toast/Alert (sử dụng react-toastify)
- Ngày 21: Responsive CSS, accessibility checks

Tuần 4 — Hoàn thiện + Deploy

- Ngày 22: Phân quyền (Admin/User) — middleware, role checks
- Ngày 23: (Optional) Kanban board UI cho tasks (Drag & Drop)
- Ngày 24: Filter/Sort tasks theo deadline/status
- Ngày 25: Dark mode (Tailwind + state)
- Ngày 26: Deploy backend (Railway/Render) — set ENV, migrate, seed
- Ngày 27: Deploy frontend (Vercel/Netlify) — ENV base API URL
- Ngày 28: End-to-end test, finalize README & API docs

## API tóm tắt (gợi ý endpoints)

- Auth
  - POST /api/register {name,email,password} → 201 + token
  - POST /api/login {email,password} → 200 + token
  - POST /api/logout (Auth)
- Projects
  - GET /api/projects
  - GET /api/projects/{id}
  - POST /api/projects
  - PUT/PATCH /api/projects/{id}
  - DELETE /api/projects/{id}
- Tasks
  - GET /api/projects/{project_id}/tasks
  - POST /api/projects/{project_id}/tasks
  - PUT/PATCH /api/tasks/{id}
  - DELETE /api/tasks/{id}
- Posts
  - GET /api/posts
  - GET /api/posts/{slug}
  - POST /api/posts
  - PUT /api/posts/{id}
  - DELETE /api/posts/{id}
- Comments
  - GET /api/posts/{post_id}/comments
  - POST /api/posts/{post_id}/comments
  - DELETE /api/comments/{id}

## DB schema tóm tắt

- users: id, name, email, password, role, timestamps
- projects: id, title, description, owner_id, status, deadline, timestamps
- tasks: id, project_id, title, description, due_date, status, assignee_id, timestamps
- posts: id, user_id, title, body, slug, published_at, timestamps
- comments: id, post_id, user_id, body, timestamps

## Quick start (local)

Backend

1. cp .env.example .env -> chỉnh DB, APP_URL
2. composer install
3. php artisan key:generate
4. php artisan migrate --seed
5. php artisan serve

Frontend

1. cd frontend
2. npm install
3. npm run dev
4. Đặt REACT_APP_API_URL hoặc VITE_API_URL để gọi API

## Testing & QA

- Viết Feature tests Laravel (authentication, project CRUD, task flow)
- Viết unit tests cho services/helpers
- Frontend: test component chính với React Testing Library

## Deploy notes

- Backend: set APP_KEY, DB_URL, SANCTUM stateful domains, CORS
- Frontend: build và set API base URL; thêm proxy khi cần

## Ghi chú triển khai nhanh

- Sử dụng Laravel Resource + FormRequest cho validation
- Dùng factories + seeders để tạo dữ liệu giả cho demo
- Bảo mật: rate limiting, policy + middleware, sanitize inputs
- Tài liệu API: tạo api/README.md hoặc Postman collection trong docs/

## Liên hệ

- Project dành cho mục tiêu portfolio
