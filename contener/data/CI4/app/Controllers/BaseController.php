<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\ConnectionInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance principale de la Request.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Helpers à charger automatiquement.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Connexion DB accessible dans tous les controllers via $this->db
     */
    protected ConnectionInterface $db;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // DB dispo partout (corrige "Undefined property ...::$db")
        $this->db = Database::connect();

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = service('session');
    }
}
