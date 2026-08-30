<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $books = [
            [
                'title' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'published_date' => '1905-01-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=1',
                'description' => '夏目漱石の代表作。猫の視点から人間社会をユーモラスに描いた長編小説。',
                'genres' => ['小説'],
            ],
            [
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=2',
                'description' => '人間関係の基本原則をわかりやすく解説した世界的ベストセラー。ビジネスから日常生活まで幅広く役立つ一冊。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=3',
                'description' => '読みやすく保守しやすいコードを書くための考え方と実践的なテクニックを紹介したプログラミング書。',
                'genres' => ['技術書'],
            ],
            [
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=4',
                'description' => '人生や仕事で成果を上げるための7つの原則を紹介する自己啓発の名著。世界中で読み継がれている一冊。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=5',
                'description' => '正義感の強い青年・坊っちゃんが教師として奮闘する姿を描いた、夏目漱石の代表的小説。',
                'genres' => ['小説'],
            ],
            [
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=6',
                'description' => '人類の歴史を壮大な視点で振り返り、文明や社会の成り立ちをわかりやすく解説した歴史書。',
                'genres' => ['歴史', '科学'],
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=7',
                'description' => 'ソフトウェア開発における品質の高いコードの書き方や設計思想を学べる、エンジニア必読の一冊。',
                'genres' => ['技術書'],
            ],
            [
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=8',
                'description' => 'アドラー心理学をもとに、自分らしく生きるための考え方を対話形式でわかりやすく解説した自己啓発書。',
                'genres' => ['自己啓発'],
            ],
            [
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=9',
                'description' => '売れない芸人同士の友情や葛藤を描いた、芥川賞受賞作。笑いと人生について考えさせられる作品。',
                'genres' => ['小説'],
            ],
            [
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=10',
                'description' => 'データや統計をもとに世界を正しく見るための考え方を学べる、思い込みを覆すベストセラー。',
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=11',
                'description' => '海上コンテナの誕生が物流や世界経済に与えた影響を、豊富な事例とともに解説したノンフィクション。',
                'genres' => ['ビジネス', '歴史'],
            ],
        ];

        foreach ($books as $data) {
            $genres = $data['genres'];
            unset($data['genres']);

            $data['user_id'] = $users->random()->id;

            $book = Book::firstOrCreate(
                ['isbn' => $data['isbn']],
                $data
            );

            $genreIds = Genre::whereIn('name', $genres)->pluck('id');

            $book->genres()->sync($genreIds);
        }
    }
}
