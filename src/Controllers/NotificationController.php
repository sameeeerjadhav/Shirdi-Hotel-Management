<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Notification;
use App\Utils\Sanitizer;

class NotificationController extends Controller {

    public function index() {
        if (!isset($_SESSION['user_id'])) { $this->json(['error' => 'Unauthorized'], 401); }
        $model    = new Notification();
        $notifs   = $model->forUser($_SESSION['user_id'], 20);
        $unread   = $model->unreadCount($_SESSION['user_id']);
        $this->json(['success' => true, 'notifications' => $notifs, 'unread' => $unread]);
    }

    public function markRead($id) {
        if (!isset($_SESSION['user_id'])) { $this->json(['error' => 'Unauthorized'], 401); }
        $model = new Notification();
        $model->markRead($id, $_SESSION['user_id']);
        $this->json(['success' => true]);
    }

    public function markAllRead() {
        if (!isset($_SESSION['user_id'])) { $this->json(['error' => 'Unauthorized'], 401); }
        $model = new Notification();
        $model->markAllRead($_SESSION['user_id']);
        $this->json(['success' => true]);
    }
}
