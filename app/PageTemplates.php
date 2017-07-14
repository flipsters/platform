<?php
namespace App;

trait PageTemplates
{


    private function with_subheader()
    {
        $this->crud->addField([   // CustomHTML
                        'name' => 'subheader_seperator',
                        'type' => 'custom_html',
                        'value' => '<br><h2>Subheader</h2><hr>',
                    ]);

        $this->crud->addField([
                        'name' => 'subheader_title',
                        'label' => 'Subheader Title',
                        'fake' => true,
                        'store_in' => 'extras',
                    ]);

        $this->crud->addField([
                        'name' => 'subheader_icon',
                        'label' => 'Subheader Icon',
                        'fake' => true,
                        'type' => 'icon_picker',
                        'store_in' => 'extras',
                    ]);

        $this->crud->addField([   // CustomHTML
                        'name' => 'content_separator',
                        'type' => 'custom_html',
                        'value' => '<br><h2>Content</h2><hr>',
                    ]);

        $this->crud->addField([
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => 'wysiwyg',
                        'placeholder' => 'Your content here',
                    ]);
    }

    private function without_subheader()
    {
        $this->crud->addField([   // CustomHTML
                        'name' => 'content_separator',
                        'type' => 'custom_html',
                        'value' => '<br><h2>Content</h2><hr>',
                    ]);

        $this->crud->addField([
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => 'wysiwyg',
                        'placeholder' => 'Your content here',
                    ]);
    }

    private function contact_form()
    {
        $this->crud->addField([   // CustomHTML
                        'name' => 'subheader_seperator',
                        'type' => 'custom_html',
                        'value' => '<br><h2>Subheader</h2><hr>',
                    ]);

        $this->crud->addField([
                        'name' => 'subheader_title',
                        'label' => 'Subheader Title',
                        'fake' => true,
                        'store_in' => 'extras',
                    ]);

        $this->crud->addField([
                        'name' => 'subheader_icon',
                        'label' => 'Subheader Icon',
                        'fake' => true,
                        'type' => 'icon_picker',
                        'store_in' => 'extras',
                    ]);

        $this->crud->addField([   // CustomHTML
                        'name' => 'content_separator',
                        'type' => 'custom_html',
                        'value' => '<br><h2>Content above contact form</h2><hr>',
                    ]);

        $this->crud->addField([
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => 'wysiwyg',
                        'placeholder' => 'Your content here',
                    ]);
    }
}
