<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/settings', name: 'admin_settings_')]
class SettingsController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        $db = new \SQLite3(__DIR__ . '/../../../var/data/default.db', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $db->enableExceptions(true);

        $db->query('CREATE TABLE IF NOT EXISTS app_settings(
                    id INTEGER PRIMARY KEY NOT NULL,
                    type VARCHAR(32) NOT NULL,
                    created_at DATETIME
                )');

        $date = date('Y-m-d H:i:s');

        $settingsCount = $db->querySingle('SELECT COUNT(id) FROM app_settings');
        $settingsCount++;

        $statement = $db->prepare('SELECT * FROM app_settings WHERE id = :id');
        $statement->bindValue(':id', $settingsCount, SQLITE3_INTEGER);
        $result = $statement->execute();

        if($result->fetchArray(SQLITE3_ASSOC) === false) {
            $db->exec('BEGIN');
            $db->query(sprintf('INSERT
                                        INTO app_settings(id, type, created_at)
                                        VALUES ("%d", "%s", "%s")',
                $settingsCount, 'type', $date));
            $db->exec('COMMIT');
        }
        $db->close();

        return $this->render('admin/settings/index.html.twig', [
            //'button' => $button
        ]);
    }
}
