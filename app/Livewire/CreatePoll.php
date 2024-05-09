<?php

namespace App\Livewire;

use App\Models\Poll;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreatePoll extends Component
{
    public $title;
    public $options = array();

    protected $rules = array(
        'title' => 'required|min:3|max:255',
        'options' => 'required|array|min:1',
        'options.*' => 'required|min:1|max:255',
    );

    protected $messages = array(
        'options.*' => "The option can't be empty."
    );

    public function mount()
    {
    }

    public function render(): View
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function createPoll()
    {
        $this->validate();

        Poll::create(array(
            'title' => $this->title
        ))->options()->createMany(
            collect($this->options)
                ->map(function ($option) {
                    return array('name' => $option);
                })
                ->all()
        );

        //foreach ($this->options as $option) {
        //    $poll->options()->create(array('name' => $option));
        //}

        $this->reset(array('title', 'options'));
        $this->dispatch('pollCreated');

    }
}
