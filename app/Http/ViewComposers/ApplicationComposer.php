<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;

class ApplicationComposer {

    /**
     * 
     *
     * @var Array
     */
    protected $alert;

    /**
     * 
     *
     * @param  Array  $alert
     * @return void
     */
    public function __construct(Array $alert)
    {
        // Dependencies automatically resolved by service container...
        $this->alert = $alert['alert'];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->share('alert', $this->alert);
    }

}
?>