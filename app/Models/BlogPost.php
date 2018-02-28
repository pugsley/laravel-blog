<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $guarded = ['id', 'user_id'];

    /**
     * BlogPost's belong to a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to get id and return correct type (string)
     * @return int
     */

    /**
     * Helper to get id and return correct type
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Helper to get title and return correct type
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Inject the url slug
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['slug'] = $this->getId() . '-' . str_slug($this->getTitle());

        // Create some human friendly values here. We'd normally set the timezone
        // based on the user preference in the database, or on the frontend with
        // moment.js.
        $array['human'] = [
            'created' => $this->getCreatedAt()->format('j F Y')
        ];

        $array['html'] = [
            'content' => "<p>" . str_replace("\n", "<br>", $array['content']) . "</p>"
        ];

        return $array;
    }

}
