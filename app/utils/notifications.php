<?php

enum NotificationType {
  case Info;
  case Warning;
  case Error;
  case Success;
}

class Notification {
  public NotificationType $type;
  public string $title = '';
  public string $message;

  public function __construct(
    NotificationType $type,
    string $message,
    string $title = ''
  ) {
    $this->type = $type;
    $this->message = $message;
    $this->title = $title;
  }

  public function class(): string {
    switch ($this->type) {
      case NotificationType::Info:
        return "alert-info";
      case NotificationType::Warning:
        return "alert-warning";
      case NotificationType::Error:
        return "alert-danger";
      case NotificationType::Success:
        return "alert-success";
      default:
        return "alert-secondary";
    }
  }
}

class Notifications {

  static function get_notifications(): array {
    return $_SESSION['__notifications'] ?? array();
  }

  static function add_notification(Notification $notification) {
    $_SESSION['__notifications'] ??= array();
    $_SESSION['__notifications'][] = $notification;

    return;
  }

  static function add_error(string $error) {
    static::add_notification(
      new Notification(
        NotificationType::Error,
        $error
      )
    );
  }

  static function has_notifications(): bool {
    return (
      isset($_SESSION['__notifications'])
      && sizeof($_SESSION['__notifications']) > 0
    );
  }

  static function notifications_shown() {
    unset($_SESSION['__notifications']);
  }
}

function NOTIFY(NotificationType $type, string $message, string $title) {
  Notifications::add_notification(
    new Notification(
      $type, $message, $title
    )
  );
}
function NOTIFY_ERROR(string $message, string $title='') {
  NOTIFY(NotificationType::Error, $message, $title);
}

function NOTIFY_WARNING(string $message, string $title='') {
  NOTIFY(NotificationType::Warning, $message, $title);
}

function NOTIFY_INFO(string $message, string $title='') {
  NOTIFY(NotificationType::Info, $message, $title);
}

function NOTIFY_SUCCESS(string $message, string $title='') {
  NOTIFY(NotificationType::Success, $message, $title);
}

?>
