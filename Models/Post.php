<?php

declare(strict_types=1);

namespace Modules\Lang\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
// --- traits ---
use Illuminate\Support\Carbon;
// use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Modules\Xot\Traits\Updater;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Modules\Lang\Models\Post.
 *
 * @property int             $id
 * @property int|null        $user_id
 * @property string|null     $post_type
 * @property int|null        $post_id
 * @property string|null     $lang
 * @property string|null     $title
 * @property string|null     $subtitle
 * @property string|null     $guid
 * @property string|null     $txt
 * @property string|null     $image_src
 * @property string|null     $image_alt
 * @property string|null     $image_title
 * @property string|null     $meta_description
 * @property string|null     $meta_keywords
 * @property int|null        $author_id
 * @property Carbon|null     $created_at
 * @property Carbon|null     $updated_at
 * @property int|null        $category_id
 * @property string|null     $image
 * @property string|null     $content
 * @property int|null        $published
 * @property string|null     $created_by
 * @property string|null     $updated_by
 * @property string|null     $url
 * @property array|null      $url_lang
 * @property array|null      $image_resize_src
 * @property string|null     $linked_count
 * @property string|null     $related_count
 * @property string|null     $relatedrev_count
 * @property string|null     $linkable_type
 * @property int|null        $views_count
 * @property Model|\Eloquent $linkable
 *
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereAuthorId($value)
 * @method static Builder|Post whereCategoryId($value)
 * @method static Builder|Post whereContent($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereCreatedBy($value)
 * @method static Builder|Post whereGuid($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereImage($value)
 * @method static Builder|Post whereImageAlt($value)
 * @method static Builder|Post whereImageResizeSrc($value)
 * @method static Builder|Post whereImageSrc($value)
 * @method static Builder|Post whereImageTitle($value)
 * @method static Builder|Post whereLang($value)
 * @method static Builder|Post whereLinkableType($value)
 * @method static Builder|Post whereLinkedCount($value)
 * @method static Builder|Post whereMetaDescription($value)
 * @method static Builder|Post whereMetaKeywords($value)
 * @method static Builder|Post wherePostId($value)
 * @method static Builder|Post wherePostType($value)
 * @method static Builder|Post wherePublished($value)
 * @method static Builder|Post whereRelatedCount($value)
 * @method static Builder|Post whereRelatedrevCount($value)
 * @method static Builder|Post whereSubtitle($value)
 * @method static Builder|Post whereTitle($value)
 * @method static Builder|Post whereTxt($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUpdatedBy($value)
 * @method static Builder|Post whereUrl($value)
 * @method static Builder|Post whereUrlLang($value)
 * @method static Builder|Post whereUserId($value)
 * @method static Builder|Post whereViewsCount($value)
 *
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    use HasSlug;
    // use Cachable;
    use Updater;

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @see  https://laravel-news.com/6-eloquent-secrets
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    protected $perPage = 30;

    // use Searchable;
    /**
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'user_id', 'post_id', 'lang', 'guid',
        'title',
        'subtitle',
        'post_type',
        'txt',
        // ------ IMAGE ---------
        'image_src', 'image_alt', 'image_title',
        // ------ SEO FIELDS -----
        'meta_description', 'meta_keywords', // seo
        'author_id',
        // ------ BUFFER ----
        'url', 'url_lang', // buffer
        'image_resize_src', // buffer
    ];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'image_resize_src' => 'array',
        'url_lang' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    /*
    public function getRouteKeyName() {
        return inAdmin() ? 'guid' : 'post_id';
    }
    */

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('guid');
    }

    // -------- relationship ------
    /**
     * @return MorphTo
     */
    public function linkable()
    {
        return $this->morphTo('post');
    }

    /* deprecated
    public function archive() {
        $lang = $this->lang;
        $post_type = $this->post_type;
        $obj = $this->getLinkedModel();
        $table = $obj->getTable();
        $post_table = with(new Post())->getTable();
        $rows = $obj->join($post_table, $post_table.'.post_id', $table.'.post_id')
                    ->where('lang', $lang)
                    ->where($post_table.'.post_type', $post_type)
                    ->where($post_table.'.guid', '!=', $post_type)
                    ->orderBy($table.'.updated_at', 'desc')
                    ->with('post')
                    ;

        return $rows;
    }
    */

    // end function
    // -------------- MUTATORS ------------------

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;
        $this->attributes['guid'] = Str::slug($value);
    }

    /**
     * Undocumented function.
     */
    public function getTitleAttribute(?string $value): ?string
    {
        if ($value !== null) {
            return $value;
        }

        if (! empty($this->attributes['post_type'])) {
            $value = $this->attributes['post_type'].' '.$this->attributes['post_id'];
        } else {
            $value = $this->post_type.' '.$this->post_id;
        }

        $this->title = $value;

        $this->save();

        return $value;
    }

    /**
     * ---.
     */
    public function getGuidAttribute(?string $value): ?string
    {
        if (\is_string($value) && $value !== '' && ! str_contains($value, ' ')) {
            return $value;
        }
        $value = $this->title;
        if ($value === '') {
            $value = $this->attributes['post_type'].' '.$this->attributes['post_id'];
        }
        if ($value === null) {
            $value = 'u-'.random_int(1, 1000);
        }
        $value = Str::slug($value);
        $this->guid = $value;
        $this->save();

        return $value;
    }

    public function getTxtAttribute(?string $value): ?string
    {
        return $value ?? '';
    }

    /*
    public function getUrlAttribute($value) {

    }
    */

    final public const SEARCHABLE_FIELDS = ['title', 'guid', 'txt'];

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }
}// end class
