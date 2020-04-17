<?php

require_once __DIR__ . '/../../.env';

class DatabaseManager {

  private PDO $pdo; // 7.4+

  public function __construct() {
    $options = [
        'host=' . DB_HOST,
        'dbname=' . DB_NAME,
        'port=' . DB_PORT
    ];
    $this->pdo = new PDO(DB_DRIVER . ':' . join(';', $options), DB_USER, DB_PASSWORD);
  }

  public function getAll(string $sql, array $params = []): array {
    $statement = $this->internalExec($sql, $params);
    if($statement === null) {
      return [];
    }
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function find(string $sql, array $params = []): ?array {
    $statement = $this->internalExec($sql, $params);
    if($statement === null) {
      return null;
    }
    $line = $statement->fetch(PDO::FETCH_ASSOC);
    if($line === false) {
      return null;
    }
    return $line;
  }

  public function exec(string $sql, array $params = []): int {
    $statement = $this->internalExec($sql, $params);
    if($statement === null) {
      return 0;
    }
    return $statement->rowCount();
  }

  private function internalExec(string $sql, array $params): ?PDOStatement {
    $statement = $this->pdo->prepare($sql);
    if($statement === false) {
      return null;
    }
    $res = $statement->execute($params);
    if($res === false) {
      return null;
    }
    return $statement;
  }

  public function getLastInsertId(): int {
    return $this->pdo->lastInsertId();
  }
}

?>
