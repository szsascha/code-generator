<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\CodeGenerator;

class CodeGeneratorController extends AbstractController
{
    /**
     * @Route("/codegen/{template}/{language}", methods={"POST"})
     */
    public function codegen(CodeGenerator $codeGenerator, Request $request, string $template, string $language): Response
    {
        return $this->render(
            'codegen/'.ucfirst($template).'.'.$language.'.twig', 
            $codeGenerator->generateTemplateModel($request->getContent())
        );
    }
}