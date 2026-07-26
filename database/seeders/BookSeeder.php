<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Genre;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'name' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'publication' => '1905-01-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=1',
                'description' => '夏目漱石の代表作。猫の視点から人間社会をユーモラスに描いた長編小説。',
                'user_id' => 1,
                'genres' => ['小説'],
            ],
            [
                'name' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'publication' => '1936-10-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=2',
                'description' => '人間関係の基本原則をわかりやすく解説した世界的ベストセラー。ビジネスから日常生活まで幅広く役立つ一冊。',
                'user_id' => 1,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'name' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'publication' => '2012-06-23',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=3',
                'description' => '読みやすく保守しやすいコードを書くための考え方と実践的なテクニックを紹介したプログラミング書。',
                'user_id' => 2,
                'genres' => ['技術書'],
            ],
            [
                'name' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'publication' => '2013-08-30',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=4',
                'description' => '人生や仕事で成果を上げるための7つの原則を紹介する自己啓発の名著。世界中で読み継がれている一冊。',
                'user_id' => 2,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'name' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'publication' => '1906-04-01',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=5',
                'description' => '正義感の強い青年・坊っちゃんが教師として奮闘する姿を描いた、夏目漱石の代表的小説。',
                'user_id' => 3,
                'genres' => ['小説'],
            ],
            [
                'name' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'publication' => '2016-09-08',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=6',
                'description' => '人類の歴史を壮大な視点で振り返り、文明や社会の成り立ちをわかりやすく解説した歴史書。',
                'user_id' => 3,
                'genres' => ['歴史', '科学'],
            ],
            [
                'name' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'publication' => '2017-12-18',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=7',
                'description' => 'ソフトウェア開発における品質の高いコードの書き方や設計思想を学べる、エンジニア必読の一冊。',
                'user_id' => 4,
                'genres' => ['技術書'],
            ],
            [
                'name' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'publication' => '2013-12-13',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=8',
                'description' => 'アドラー心理学をもとに、自分らしく生きるための考え方を対話形式でわかりやすく解説した自己啓発書。',
                'user_id' => 4,
                'genres' => ['自己啓発'],
            ],
            [
                'name' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'publication' => '2015-03-11',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=9',
                'description' => '売れない芸人同士の友情や葛藤を描いた、芥川賞受賞作。笑いと人生について考えさせられる作品。',
                'user_id' => 5,
                'genres' => ['小説'],
            ],
            [
                'name' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'publication' => '2019-01-11',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=10',
                'description' => 'データや統計をもとに世界を正しく見るための考え方を学べる、思い込みを覆すベストセラー。',
                'user_id' => 5,
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'name' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'publication' => '2007-01-18',
                'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=11',
                'description' => '海上コンテナの誕生が物流や世界経済に与えた影響を、豊富な事例とともに解説したノンフィクション。',
                'user_id' => 1,
                'genres' => ['ビジネス', '歴史'],
            ],
        ];

        foreach ($books as $data) {
            $genres = $data['genres'];
            unset($data['genres']);

            $book = Book::firstOrCreate(
                ['isbn' => $data['isbn']],
                $data
            );

            $genreIds = Genre::whereIn('name', $genres)->pluck('id');

            $book->genres()->sync($genreIds);
        }
        // $data /本1冊分の配列
        // $genres /ジャンル名の配列
        // $book /Bookモデル
        // $genreIds /ジャンルIDの配列
    }
}
