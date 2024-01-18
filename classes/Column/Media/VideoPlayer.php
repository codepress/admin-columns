<?php

namespace AC\Column\Media;

use AC\ApplyFilter\ValidVideoMimetypes;
use AC\Column;
use AC\Settings\Column\VideoDisplay;
use AC\View\Embed\Video;

class VideoPlayer extends Column implements Column\AjaxValue
{

    public function __construct()
    {
        $this->set_type('column-video_player')
             ->set_group('media-video')
             ->set_label(__('Video Player', 'codepress-admin-columns'));
    }

    protected function register_settings()
    {
        parent::register_settings();

        $this->add_setting(new VideoDisplay());
    }

    private function get_display_type()
    {
        return $this->get_option('video_display');
    }

    private function create_relative_path($url)
    {
        return str_replace(site_url(), '', $url);
    }

    public function get_value($id)
    {
        $url = $this->get_raw_value($id);

        if ( ! $url) {
            return $this->get_empty_char();
        }

        if ('modal' === $this->get_display_type()) {
            $url = $this->get_raw_value($id);

            return ac_helper()->html->get_ajax_modal_link(
                __('Play', 'codepress-admin-columns'),
                [
                    'title'         => get_the_title($id),
                    'edit_link'     => get_edit_post_link($id),
                    'download_link' => $this->create_relative_path($url) ?: null,
                    'id'            => $id,
                    'class'         => "-nopadding",
                ]
            );
        }

        return $this->get_video_embed($url);
    }

    private function is_valid_mime_type($id)
    {
        return in_array($this->get_mime_type($id), (new ValidVideoMimetypes($this))->apply_filters());
    }

    private function get_mime_type($id)
    {
        return get_post_field('post_mime_type', $id);
    }

    private function get_video_embed($url, array $attributes = [])
    {
        $view = new Video($attributes);
        $view->set_src($url);

        return $view->render();
    }

    public function get_raw_value($id)
    {
        return $this->is_valid_mime_type($id)
            ? wp_get_attachment_url($id)
            : false;
    }

    public function get_ajax_value($id)
    {
        $url = $this->get_raw_value($id);

        return $url
            ? $this->get_video_embed($url, ['width' => 600, 'autoplay' => 'true'])
            : null;
    }

}