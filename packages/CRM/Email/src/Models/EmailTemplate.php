<?php

namespace CRM\Email\Models;

use CRM\Core\Models\BaseModel;
use CRM\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class EmailTemplate extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'email_templates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body_html',
        'body_text',
        'description',
        'category',
        'type',
        'language',
        'variables',
        'settings',
        'is_active',
        'is_default',
        'user_id',
        'parent_id',
        'version',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'variables' => 'array',
        'settings' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'version' => 'integer',
    ];

    /**
     * Template types
     */
    const TYPE_SYSTEM = 'system';
    const TYPE_USER = 'user';
    const TYPE_CAMPAIGN = 'campaign';
    const TYPE_AUTOMATED = 'automated';
    const TYPE_TRANSACTIONAL = 'transactional';

    /**
     * Template categories
     */
    const CATEGORY_WELCOME = 'welcome';
    const CATEGORY_FOLLOW_UP = 'follow_up';
    const CATEGORY_REMINDER = 'reminder';
    const CATEGORY_NOTIFICATION = 'notification';
    const CATEGORY_MARKETING = 'marketing';
    const CATEGORY_SUPPORT = 'support';

    /**
     * Get the user who created the template.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent template (for versioning).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'parent_id');
    }

    /**
     * Get child templates (versions).
     */
    public function versions(): HasMany
    {
        return $this->hasMany(EmailTemplate::class, 'parent_id');
    }

    /**
     * Get emails that used this template.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'template_id');
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default templates.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for templates by category.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for templates by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for templates by language.
     */
    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', $language);
    }

    /**
     * Scope for system templates.
     */
    public function scopeSystem(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_SYSTEM);
    }

    /**
     * Scope for user templates.
     */
    public function scopeUser(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_USER);
    }

    /**
     * Get the latest version of this template.
     */
    public function getLatestVersion(): ?EmailTemplate
    {
        if ($this->parent_id) {
            return $this->parent->versions()->orderBy('version', 'desc')->first();
        }
        
        return $this->versions()->orderBy('version', 'desc')->first() ?? $this;
    }

    /**
     * Create a new version of this template.
     */
    public function createVersion(array $attributes = []): EmailTemplate
    {
        $parentId = $this->parent_id ?? $this->id;
        $latestVersion = $this->getLatestVersion();
        $newVersion = $latestVersion ? $latestVersion->version + 1 : 1;

        return self::create(array_merge($this->toArray(), $attributes, [
            'id' => null,
            'parent_id' => $parentId,
            'version' => $newVersion,
            'created_at' => null,
            'updated_at' => null,
        ]));
    }

    /**
     * Render the template with variables.
     */
    public function render(array $variables = []): array
    {
        $engine = config('crm.email.templates.engine', 'twig');
        
        switch ($engine) {
            case 'twig':
                return $this->renderWithTwig($variables);
            case 'blade':
                return $this->renderWithBlade($variables);
            default:
                return $this->renderWithSimpleReplace($variables);
        }
    }

    /**
     * Render template with Twig engine.
     */
    protected function renderWithTwig(array $variables = []): array
    {
        $loader = new \Twig\Loader\ArrayLoader([
            'subject' => $this->subject,
            'body_html' => $this->body_html,
            'body_text' => $this->body_text,
        ]);
        
        $twig = new \Twig\Environment($loader);
        
        return [
            'subject' => $twig->render('subject', $variables),
            'body_html' => $twig->render('body_html', $variables),
            'body_text' => $twig->render('body_text', $variables),
        ];
    }

    /**
     * Render template with Blade engine.
     */
    protected function renderWithBlade(array $variables = []): array
    {
        $blade = app('view');
        
        return [
            'subject' => $blade->make('string', $this->subject, $variables)->render(),
            'body_html' => $blade->make('string', $this->body_html, $variables)->render(),
            'body_text' => $blade->make('string', $this->body_text, $variables)->render(),
        ];
    }

    /**
     * Render template with simple variable replacement.
     */
    protected function renderWithSimpleReplace(array $variables = []): array
    {
        $subject = $this->subject;
        $bodyHtml = $this->body_html;
        $bodyText = $this->body_text;

        foreach ($variables as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            $subject = str_replace($placeholder, $value, $subject);
            $bodyHtml = str_replace($placeholder, $value, $bodyHtml);
            $bodyText = str_replace($placeholder, $value, $bodyText);
        }

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    /**
     * Get available variables for this template.
     */
    public function getAvailableVariables(): array
    {
        return $this->variables ?? config('crm.email.templates.variables', []);
    }

    /**
     * Validate template syntax.
     */
    public function validateSyntax(): array
    {
        $errors = [];
        $engine = config('crm.email.templates.engine', 'twig');

        try {
            if ($engine === 'twig') {
                $loader = new \Twig\Loader\ArrayLoader([
                    'subject' => $this->subject,
                    'body_html' => $this->body_html,
                    'body_text' => $this->body_text,
                ]);
                
                $twig = new \Twig\Environment($loader);
                $twig->parse($twig->tokenize(new \Twig\Source($this->subject, 'subject')));
                $twig->parse($twig->tokenize(new \Twig\Source($this->body_html, 'body_html')));
                $twig->parse($twig->tokenize(new \Twig\Source($this->body_text, 'body_text')));
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }

    /**
     * Clone template.
     */
    public function duplicate(array $attributes = []): EmailTemplate
    {
        $clone = $this->replicate();
        $clone->name = $attributes['name'] ?? $this->name . ' (Copy)';
        $clone->slug = $attributes['slug'] ?? $this->slug . '-copy';
        $clone->is_default = false;
        $clone->save();

        return $clone;
    }
}
