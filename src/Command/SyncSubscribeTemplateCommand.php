<?php

namespace WechatMiniProgramSubscribeMessageBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;
use WechatMiniProgramSubscribeMessageBundle\Request\GetPrivateTemplateListRequest;

#[AsCronTask('15 */4 * * *')]
#[AsCommand(name: 'wechat-mini-program:sync-subscribe-template', description: '定期同步订阅消息模板到本地')]
class SyncSubscribeTemplateCommand extends Command
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Client $client,
        private readonly SubscribeTemplateRepository $subscribeTemplateRepository,
        private readonly SubscribeParamRepository $subscribeParamRepository,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findBy(['valid' => true]);
        foreach ($accounts as $account) {
            $request = new GetPrivateTemplateListRequest();
            $request->setAccount($account);
            try {
                $response = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error("拉取小程序模板ID失败:[{$account->getAppId()}]", [
                    'exception' => $exception,
                    'account' => $account,
                ]);
                continue;
            }

            if (!isset($response['data'])) {
                continue;
            }

            foreach ($response['data'] as $datum) {
                $template = $this->subscribeTemplateRepository->findOneBy([
                    'account' => $account,
                    'priTmplId' => $datum['priTmplId'],
                ]);
                if (!$template) {
                    $template = new SubscribeTemplate();
                    $template->setAccount($account);
                    $template->setPriTmplId($datum['priTmplId']);
                }
                $template->setType(SubscribeTemplateType::tryFrom($datum['type']));
                $template->setTitle($datum['title']);
                $template->setContent($datum['content']);
                $template->setExample($datum['example']);
                $template->setValid(true);
                $this->entityManager->persist($template);
                $this->entityManager->flush();

                // 枚举值保存起来
                if (isset($datum['keywordEnumValueList']) && $datum['keywordEnumValueList']) {
                    foreach ($datum['keywordEnumValueList'] as $enumDatum) {
                        $codeKey = str_replace('.DATA', '', $enumDatum['keywordCode']);
                        $param = $this->subscribeParamRepository->findOneBy([
                            'template' => $template,
                            'code' => $codeKey,
                        ]);
                        if (!$param) {
                            $param = new SubscribeParam();
                            $param->setTemplate($template);
                            $param->setCode($codeKey);
                            $param->setType(SubscribeTemplateData::ENUM);
                        }
                        $param->setEnumValues($datum['enumValueList']);
                        $this->entityManager->persist($param);
                        $this->entityManager->flush();
                        $this->entityManager->detach($param);
                    }
                }

                // 解析内容的参数，然后写到数据库
                preg_match_all('@{{(.*?).DATA}}@', $template->getContent(), $matches);
                foreach ($matches[1] as $codeKey) {
                    $param = $this->subscribeParamRepository->findOneBy([
                        'template' => $template,
                        'code' => $codeKey,
                    ]);
                    if (!$param) {
                        $param = new SubscribeParam();
                        $param->setTemplate($template);
                        $param->setCode($codeKey);

                        preg_match("@(.*?)(\d+)@", $codeKey, $match);
                        $param->setType(SubscribeTemplateData::tryFrom($match[1]));
                    }
                    $this->entityManager->persist($param);
                    $this->entityManager->flush();
                    $this->entityManager->detach($param);
                }
            }
        }

        return Command::SUCCESS;
    }
}
