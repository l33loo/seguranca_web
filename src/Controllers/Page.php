<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Page\InvalidPageException;
use App\Page\PageReader;
use App\Template\Renderer;
use Http\Response;


class Page
{
    private Response  $response;
    private Renderer $renderer;
    private PageReader $pageReader;
    
    public function __construct(
        Response $response,
        Renderer $renderer,
        PageReader $pageReader,
    ) {
        $this->response = $response;
        $this->renderer = $renderer;
        $this->pageReader = $pageReader;
    }

    public function show($params)
    {
        $slug = $params['slug'];
        $data = [];
        
        try {
            $data['content'] = $this->pageReader->readBySlug($slug);
        } catch (InvalidPageException $e) {
            $this->response->setStatusCode(404);
            return $this->response->setContent('404 - Page not found');
        }

        
        $html = $this->renderer->render('Page', $data);
        $this->response->setContent($html);
    }
}