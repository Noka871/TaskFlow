<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_task_')]
class TaskController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->json($tasks);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Создание задачи...
    }

    // Другие методы: update, delete...
}
#[Route('', name: 'create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $task = new Task();
    $task->setTitle($data['title'] ?? '');
    $task->setDescription($data['description'] ?? null);
    $task->setIsCompleted($data['isCompleted'] ?? false);

    $em->persist($task);
    $em->flush();

    return $this->json($task, 201);
}

#[Route('/{id}', name: 'update', methods: ['PUT'])]
public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
{
    $task = $em->getRepository(Task::class)->find($id);
    if (!$task) {
        return $this->json(['error' => 'Task not found'], 404);
    }

    $data = json_decode($request->getContent(), true);
    $task->setTitle($data['title'] ?? $task->getTitle());
    $task->setDescription($data['description'] ?? $task->getDescription());
    $task->setIsCompleted($data['isCompleted'] ?? $task->isIsCompleted());

    $em->flush();

    return $this->json($task);
}

#[Route('/{id}', name: 'delete', methods: ['DELETE'])]
public function delete(int $id, EntityManagerInterface $em): JsonResponse
{
    $task = $em->getRepository(Task::class)->find($id);
    if (!$task) {
        return $this->json(['error' => 'Task not found'], 404);
    }

    $em->remove($task);
    $em->flush();

    return $this->json(['message' => 'Task deleted']);
}
🚨 Важно: добавить валидацию и сериализацию!
В Entity Task.php добавь аннотации для валидации:

php
use Symfony\Component\Validator\Constraints as Assert;

// ...

#[ORM\Column(length: 255)]
#[Assert\NotBlank]
#[Assert\Length(min: 3, max: 255)]
private ?string $title = null;
📦 Установи необходимые пакеты Symfony:
bash
composer require validator
composer require serializer
🔗 Настрой CORS (чтобы React мог обращаться к API):
Установи пакет:

bash
composer require nelmio/cors-bundle
В config/packages/nelmio_cors.yaml добавь:

yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Link']
        max_age: 3600
    paths:
        '^/api/':
            allow_origin: ['*']
            allow_headers: ['*']
            allow_methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
            max_age: 3600
В .env добавь:

text
CORS_ALLOW_ORIGIN=^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$
🐘 Настрой базу данных (в .env):
text
DATABASE_URL="mysql://root:@127.0.0.1:3306/taskflow?serverVersion=8.0"
Затем создай БД и миграцию:

bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
🚀 Запусти сервер:
bash
symfony server:start
Сервер будет на http://localhost:8000

📡 Протестируй API в Postman:
GET http://localhost:8000/api/tasks

POST http://localhost:8000/api/tasks с JSON телом:

json
{
    "title": "Первая задача",
    "description": "Описание",
    "isCompleted": false
}
✅ Что дальше:
Заверши TaskController.php

Настрой CORS

Создай БД и выполни миграции

Протестируй API

Переходи к React-части

Если будут ошибки — покажи, помогу исправить!

а это куда #[Route('', name: 'create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    $task = new Task();
    $task->setTitle($data['title'] ?? '');
    $task->setDescription($data['description'] ?? null);
    $task->setIsCompleted($data['isCompleted'] ?? false);

    $em->persist($task);
    $em->flush();

    return $this->json($task, 201);
}

#[Route('/{id}', name: 'update', methods: ['PUT'])]
public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
{
    $task = $em->getRepository(Task::class)->find($id);
    if (!$task) {
        return $this->json(['error' => 'Task not found'], 404);
    }

    $data = json_decode($request->getContent(), true);
    $task->setTitle($data['title'] ?? $task->getTitle());
    $task->setDescription($data['description'] ?? $task->getDescription());
    $task->setIsCompleted($data['isCompleted'] ?? $task->isIsCompleted());

    $em->flush();

    return $this->json($task);
}

#[Route('/{id}', name: 'delete', methods: ['DELETE'])]
public function delete(int $id, EntityManagerInterface $em): JsonResponse
{
    $task = $em->getRepository(Task::class)->find($id);
    if (!$task) {
        return $this->json(['error' => 'Task not found'], 404);
    }

    $em->remove($task);
    $em->flush();

    return $this->json(['message' => 'Task deleted']);
} и можешь распить код под структуру
Этот код нужно добавить внутрь класса TaskController, после метода index и перед закрывающей фигурной скобкой класса.

Вот полный код TaskController.php с твоей структурой:

php
<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_task_')]
class TaskController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->json($tasks);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? null);
        $task->setIsCompleted($data['isCompleted'] ?? false);

        $em->persist($task);
        $em->flush();

        return $this->json($task, 201);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        return $this->json($task);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setIsCompleted($data['isCompleted'] ?? $task->isIsCompleted());

        $em->flush();

        return $this->json($task);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);
        if (!$task) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $em->remove($task);
        $em->flush();

        return $this->json(['message' => 'Task deleted']);
    }
}
📁 Полная структура файла будет:
text
taskflow-backend/src/Controller/TaskController.php
├── namespace App\Controller;
├── use ... (импорты)
├── #[Route(...)] class TaskController
│   ├── index()    → GET /api/tasks
│   ├── create()   → POST /api/tasks
│   ├── show()     → GET /api/tasks/{id}
│   ├── update()   → PUT /api/tasks/{id}
│   └── delete()   → DELETE /api/tasks/{id}
└── } // конец класса
🔧 Дополнительно нужно добавить методы в Entity/Task.php:
php
<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $isCompleted = false;

    // Геттеры и сеттеры:

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function isIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;
        return $this;
    }
}
🚀 Теперь выполни команды:
bash
# В папке taskflow-backend:
composer require validator
composer require nelmio/cors-bundle

# Настрой БД в .env
# Затем:
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Запусти сервер:
symfony server:start
API будет доступно по адресу: http://localhost:8000/api/tasks

📡 Проверь в Postman:
GET http://localhost:8000/api/tasks → пустой массив []

POST http://localhost:8000/api/tasks с JSON → создаст задачу

GET http://localhost:8000/api/tasks/1 → покажет задачу

Если всё работает — переходи к React-части!

https://github.com/Noka871/TaskFlow.git
Отлично! У тебя уже есть репозиторий на GitHub.

🚀 Как связать локальный проект с твоим GitHub-репозиторием:
1. Инициализируй Git в папке проекта:
bash
cd /путь/к/TaskFlow
git init
2. Добавь удалённый репозиторий:
bash
git remote add origin https://github.com/Noka871/TaskFlow.git
3. Проверь связь:
bash
git remote -v
Должно показать:

text
origin  https://github.com/Noka871/TaskFlow.git (fetch)
origin  https://github.com/Noka871/TaskFlow.git (push)
📦 Структура для коммита (рекомендую):
text
TaskFlow/
├── backend/          # Symfony проект
├── frontend/         # React проект
├── .gitignore        # Важно!
└── README.md
📄 Создай .gitignore для Symfony + React:
Файл .gitignore в корневой папке TaskFlow:

gitignore
# Symfony
/.env
/vendor/
/backend/var/
/backend/node_modules/
/backend/public/bundles/
/backend/phpunit.xml
/backend/translations/

# React
/frontend/node_modules/
/frontend/build/
/frontend/.env
/frontend/.env.local
/frontend/.env.development.local
/frontend/.env.test.local
/frontend/.env.production.local
/frontend/npm-debug.log*
/frontend/yarn-debug.log*
/frontend/yarn-error.log*

# IDE
.idea/
.vscode/
*.sublime-*


