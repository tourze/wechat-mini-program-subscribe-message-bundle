<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramSubscribeMessageBundle\Service\TemplateMessageSendService;

final class TemplateMessageSendController extends AbstractController
{
    public function __construct(
        private readonly TemplateMessageSendService $sendService,
        private readonly AccountRepository $accountRepository,
    ) {
    }

    #[Route(path: '/admin/wechat-mini-program-subscribe-message/send', name: 'wechat_mini_program_subscribe_message_send_form')]
    #[IsGranted(attribute: 'ROLE_ADMIN')]
    public function __invoke(): Response
    {
        $accounts = $this->accountRepository->findBy(['valid' => true], ['name' => 'ASC']);
        $templates = $this->sendService->getAvailableTemplates();

        return $this->render('@WechatMiniProgramSubscribeMessage/admin/send_template_message.html.twig', [
            'accounts' => $accounts,
            'templates' => $templates,
        ]);
    }
}
