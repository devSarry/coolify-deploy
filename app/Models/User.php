<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_program_public'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function movieProgram() : HasOne
    {
        return $this->hasOne(MovieProgram::class);
    }


    public function scheduledMovies(): HasMany
    {
        return $this->hasMany(ScheduledMovie::class);
    }


    /**
     * @throws Exception
     */
    public function getDefaultMovieProgramId(): int
    {
        // Fetch the MovieProgram ID for the authenticated user
        // Adjust the query logic as needed
        $movieProgram = $this->movieProgram()->latest()->first();

        abort_if(is_null($movieProgram), 500, 'No movie program found for the authenticated user');

        return $movieProgram->id;
    }

    public function getDefaultMovieProgram(): MovieProgram
    {
        // Fetch the MovieProgram ID for the authenticated user
        // Adjust the query logic as needed
        $movieProgram = $this->movieProgram()->latest()->first();

        abort_if(is_null($movieProgram), 500, 'No movie program found for the authenticated user');

        return $movieProgram;
    }
}
