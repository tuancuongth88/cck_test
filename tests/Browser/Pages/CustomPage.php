<?php


namespace Tests\Browser\Pages;


use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class CustomPage extends Page
{
    public function hoverMenu(Browser $browser, $menuSelector, $menuItemSelector)
    {
        $browser->script("$('$menuSelector').hover();");
        $browser->waitFor("$menuItemSelector:hover");
        $browser->script("$('$menuItemSelector').click();");
        $browser->pause(500); // Đợi một thời gian nhỏ để menu hoặc dropdown hiện ra

        return $this;
    }

    public function url()
    {
        // TODO: Implement url() method.
    }
}
