<?php
declare(strict_types=1);

namespace App\Modules\Main\Domain\Menu\Event\Subscriber;

use App\Modules\Main\Domain\Menu\Event\MenuTrait;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    use MenuTrait;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(RequestStack $request, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->request = $request;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu = $this->printMenu($this->getNavigation(), $menu, $event);

        $menu = $this->printMenu($this->getAdmin(), $menu, $event);
    }

    private function printMenu(array $table, $menu, KnpMenuEvent $event, $child = null)
    {
        foreach ($table as $index => $nav) {
            if ($this->checkRoleIsGranted($nav['granted'] ?? null)) {
                [$params, $show] = $this->prepareParamsToRoute($nav['params'] ?? []);
                if ($show) {
                    if ($child) {
                        $m = $menu;
                        foreach ($child as $ch) {
                            $m = $m->getChild($ch);
                        }

                        $m->addChild(
                            $index . $nav['route'],
                            $this->setParamsToChild($nav, $params, $event)
                        )
                          ->setExtra('translation_domain', 'admin_main')
                          ->setLabelAttribute('icon', isset($nav['icon']) ? $nav['icon'] : '');
                    } else {
                        $menu->addChild(
                            $index . $nav['route'],
                            $this->setParamsToChild($nav, $params, $event)
                        )
                             ->setExtra('translation_domain', 'admin_main')
                             ->setLabelAttribute('icon', isset($nav['icon']) ? $nav['icon'] : '');
                    }
                }

                if (isset($nav['child'])) {
                    $menu = $this->printMenu(
                        $nav['child'],
                        $menu,
                        $event,
                        ($child ? array_merge($child, [$index . $nav['route']]) : [$index . $nav['route']])
                    );
                }
            }
        }

        return $menu;
    }

    private function setParamsToChild(array $nav, array $params, KnpMenuEvent $event): array
    {
        $label = $nav['label'];
        if (isset($nav['count'])) {
            $value = $this->{$nav['count']}();
            if ($value > 0) {
                $label .= '&nbsp;<small class="label pull-right">&nbsp;</small>';
                $label .= '<small class="label pull-right' . (isset($nav['count_color']) ? ' ' . $nav['count_color'] : ' bg-red') . '">';
                $label .= $value;
                $label .= '</small>';
            }
        }

        return [
            'route' => $nav['route'],
            'routeParameters' => $params,
            'label' => $label,
            'extras' => [
                'translation_domain' => 'admin_main',
                'safe_label' => true,
            ],
            'childOptions' => $event->getChildOptions(),
            'displayChildren' => isset($nav['display']) ? $nav['display'] : false,
        ];
    }

    private function checkRoleIsGranted(?string $grantedVoter): bool
    {
        if ($grantedVoter === null) {
            return true;
        }

        if ($this->authorizationChecker->isGranted($grantedVoter)) {
            return true;
        }

        return false;
    }

    private function prepareParamsToRoute(array $params): array
    {
        $show = true;
        $params_ = [];

        if (isset($params)) {
            foreach ($params as $key => $value) {
                if ($value === '__request__' || is_array($value)) {
                    if ($this->request->getMasterRequest()->get((is_array($value) ? $value[1] : $key))) {
                        $get = $this->request->getMasterRequest()->get((is_array($value) ? $value[1] : $key));
                        if (is_object($get)) {
                            $params_[$key] = $get->getId();
                        } else {
                            $params_[$key] = $get;
                        }
                    } else {
                        $show = false;
                    }
                } else {
                    $params_[$key] = $value;
                }
            }
        }

        return [$params_, $show];
    }
}