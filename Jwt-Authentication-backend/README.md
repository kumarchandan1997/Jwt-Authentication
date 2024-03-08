#Authentication using jwt token and connect frontend and api project

1.run this command => composer require tymon/jwt-auth
2.add this line in app.php Providers =>Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
3.set vendor => php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

4.After this command it make one file jwt in config
5.After thst run one command => php artisan jwt:secret(this make one secret key in env file)
6.run php artisan migrate
7.write code in user model 

   use Tymon\JWTAuth\Contracts\JWTSubject;

   class User extends Authenticatable implements JWTSubject
  {
    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return[];
    }
  }

  8.write code in auth file

   'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api'=> [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ]
    ],

    9.Generate token for login api

     
