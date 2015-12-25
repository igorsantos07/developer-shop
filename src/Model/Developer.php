<?php namespace Shop\Model;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;

class Developer {

    /** @var string */ public $username;
    /** @var string */ public $url;
    /** @var string */ public $name;
    /** @var string */ public $avatar;
    /** @var string */ public $location  = '';
    /** @var string */ public $email     = '';
    /** @var string */ public $bio       = '';
    /** @var int */    public $repos     = 0;
    /** @var int */    public $gists     = 0;
    /** @var int */    public $followers = 0;
    /** @var int */    public $following = 0;
    /** @var array */  protected $urls   = [];

    /** @var int */    public $rate        = 0;
    /**
     * More information on each detail can be found in the calc method.
     * @see calculateHourlyRate
     * @var array
     */
    public $rateDetails = [
        'following'     => 0,
        'followers'     => 0,
        'gists'         => 0,
        'gist_comments' => 0,
        'gist_forks'    => 0,
        'gist_commits'  => 0,
        'repos'         => 0,
        'repo_stars'    => 0,
        'repo_forks'    => 0,
        'repo_commits'  => 0,
        'repo_watchers' => 0,
//        'total_commits' => [],
    ];

    const ERR_NOT_FOUND = 1;

    protected static $fieldRelations = [
        'avatar_url'   => 'avatar',
        'login'        => 'username',
        'html_url'     => 'url',
        'public_repos' => 'repos',
        'public_gists' => 'gists',
    ];

    protected static $rateMultipliers = [
        'repos'     => 5, //cool! he codes so great he has shared amazing code with the world
        'gists'     => 3,  //he has some code good enough to be shared, but not to make a full blown repo

    ];

    /** @var Guzzle */
    protected $github;

    public function __construct($username) {
        $this->github = new Guzzle([
            'base_uri' => 'https://api.github.com',
            'query'    => [
                'client_id'     => GITHUB_CLIENT,
                'client_secret' => GITHUB_SECRET
            ]
        ]);

        try {
            $user = $this->github->get('users/'.$username);
        } catch (ClientException $e) {
            if ($e->getCode() == HTTP_NOT_FOUND) {
                throw new \Exception((string)$e->getRequest()->getUri(), self::ERR_NOT_FOUND, $e);
            } else {
                throw $e;
            }
        }

        $this->importData(json_decode($user->getBody(), true));
        $this->calculateHourlyRate();
    }

    public function importData(array $data) {
        foreach ($data as $key => $value) {
            if ($value) {
                if (property_exists(static::class, $key)) {
                    $this->$key = $value;
                } elseif (array_key_exists($key, static::$fieldRelations)) {
                    $this->{static::$fieldRelations[$key]} = $value;
                }
            }
        }

        //gets all URLs from the user data and strips the '_url' portion of the keys
        $this->urls = array_filter($data, function($k) { return strpos($k, '_url') !== false; }, ARRAY_FILTER_USE_KEY);
        $this->urls = array_combine(
            array_map(function($k) { return substr($k, 0, strpos($k, '_url')); }, array_keys($this->urls)),
            array_values($this->urls)
        );
    }

    /**
     * Calculates the developer hourly rate based on their public GitHub stats.
     * Rate basis:
     * - each user they follow: 10¢     well, they're active anyway
     * - each user follow them: 20¢     hey, people like they!
     * - each gist: 30¢                 they've even got some cool code to share - but not enough for a full blown repo
     * - each fork of those gists: 10¢  these gists even have their own forks!
     * - each comment/commit on those gists: 1¢   github does not gives us star count, so we are going for comments
     * - each repository: 50¢           their code is so awesome they were able to pack it into a nice repository!
     * - each fork of those repos: 20¢  people like their code so much they even needed to fork to help it!
     * - each star/commit on those repos: 2¢ code so lovely people had to star it / history worths here!
     * - each watcher not starred on repos: 2¢ as users become watchers when they star, we're not counting them twice. Also, if someone stars the repo but do not watch, there's a 2¢ penalty
     * @todo should we remove some cents for each issue on the repos?
     * @todo improve performance by leveraging async and batch requests
     * @todo commit counting is not working correctly, as they are paged and we're getting at most 30 per repo
     */
    protected function calculateHourlyRate() {
        $rates = $GLOBALS['cache']->get('rates.'.$this->username);
        if (!$rates) {
            $rates = $this->rateDetails;
            $clear_github_url = function($url) {
                return preg_replace('|\{.*\}|', '', $url);
            };

            $rates['following'] = $this->following * 0.1;
            $rates['followers'] = $this->followers * 0.2;

            $gists = $this->github->get($clear_github_url($this->urls['gists']));
            foreach (json_decode($gists->getBody(), true) as $gist) {
                $rates['gists'] += 0.3;
                $rates['gist_comments'] += $gist['comments'] * 0.01;
                $forks   = json_decode($this->github->get($gist['forks_url'])->getBody(), true);
                try {
                    $commits = json_decode($this->github->get($gist['commits_url'])->getBody(), true);
                    $rates['gist_forks']   += sizeof($forks) * 0.1;
                    $rates['gist_commits'] += sizeof($commits) * 0.01;
//                    $rates['total_commits'][$gist['id']] = sizeof($commits);
                }
                catch (ClientException $e) {
                    //a 409 means there are no commits
                    //TODO: any other error means we won't get commits anyway, so let's just zero this
                    //there is no docs in the API regarding error codes :(
                }
            }

            $repos = $this->github->get($clear_github_url($this->urls['repos']));
            foreach (json_decode($repos->getBody(), true) as $repo) {
                $rates['repos']         += 0.5;
                $rates['repo_stars']    += $repo['stargazers_count'] * 0.02;
                $rates['repo_watchers'] += ($repo['watchers_count'] - $repo['stargazers_count']) * 0.02;
                $rates['repo_forks']    += $repo['forks_count'] * 0.2;
                try {
                    $commits = json_decode($this->github->get($clear_github_url($repo['commits_url']))->getBody(), true);
                    $rates['repo_commits'] += sizeof($commits) * 0.02;
//                    $rates['total_commits'][$repo['id']] = sizeof($commits);
                }
                catch (ClientException $e) {
                    //a 409 means there are no commits
                    //TODO: any other error means we won't get commits anyway, so let's just zero this
                    //there is no docs in the API regarding error codes :(
                }
            }

            $GLOBALS['cache']->set('rates.'.$this->username, $rates, ONE_WEEK);
        }

        $this->rateDetails = $rates;
        $this->rate = array_sum($rates);
    }

}