<?php declare(strict_types=1);

namespace App\Components\Dashboard\UI;

use App\Core\UI\CoreControl;
use Nette;

/**
 * @property-read ?string $title
 * @property-read ?string $icon
 * @property-read ?string $color
 */
abstract class BaseControl extends CoreControl
{
    use Nette\SmartObject;

    private ?string $title = null;

    private ?string $icon = null;

    private ?string $color = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title = null): self
    {
        $this->title = $title;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon = null): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color = 'primary'): self
    {
        $this->color = $color;
        return $this;
    }

    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->title = $this->title;
        $this->template->icon = $this->icon;
        $this->template->color = $this->color;
    }
}