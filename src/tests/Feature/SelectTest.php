<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Tests\TestCase;
use Faker\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


class SelectTest extends TestCase
{
    // テスト後にデータベースをリセット
    use RefreshDatabase;

    public function test_select()
    {
        // // ユーザを作る
        // $users = User::factory(6)->create();

        // // 商品を設定
        // $this->seed(ConditionsTableSeeder::class);
        // $this->seed(ItemsTableSeeder::class);

        // // 未ログイン
        // $this->assertFalse( Auth::check() );

        // // ログイン状態にする(id=6の人)
        // $user = User::where( 'id', 6 )->first();
        // $this->actingAs( $user );

        // // ユーザがログインできたか
        // $this->assertTrue( Auth::check() );

        // // ユーザがメール認証できているか
        // $this->assertTrue( Auth::user()->hasVerifiedEmail() );

        // // 個別購入ページへのアクセス
        // $item = Item::first();
        // $response = $this->get( '/purchase/' . $item['id'] );
        // $response->assertViewIs('purchase');
        // $response->assertStatus(200);






        // テスト対象のHTMLをDOMとしてロード（文字化けするからとりあえず英語）
        $dom = new \DOMDocument();
        // $dom->load( './resources/views/purchase.blade.php' ); //Laravelの記述方式だと読み込めないみたい
        $dom->loadHTML('
            <form action=" /purchase/1 " method="POST">
                @csrf

                <select name="payment" id="select" onChange="updateDisplay()">
                    <option value="">
                        Chose your payment.
                    </option>
                    <option value="convenience">
                        CONVENIENCE STORE
                    </option>
                    <option value="credit">
                        CREDIT CARD
                    </option>
                </select>

                <div class="display-ttl">PAYMENT</div>
                <div id="displayArea"></div>
                <script>
                    function updateDisplay() {
                        var selectedValue = document.getElementById("select").value;
                        const num = select.selectedIndex;
                        document.getElementById("displayArea").textContent = select.options[num].innerText;
                    }
                </script>

                <button class="purchase-btn" type="submit">PAY</button>
            </form>
        ');

        // セレクトボックスの全要素を取得
        $selectElement = $dom->getElementById( 'select' );

        // 選択項目
        $faker = Factory::create();
        $select = $faker->randomElement( [ 'convenience', 'credit' ] );

        // 選択したいオプションのvalue属性値を設定
        $selectElement->setAttribute( 'value', $select );

        // 選択できたか
        $this->assertEquals( $select, $selectElement->getAttribute('value') );

        // 選択された<option>要素を取得
        $selectedOption = null;
        foreach( $selectElement->childNodes as $child ){
            if( $child instanceof \DOMElement &&
                $child->tagName === 'option' &&
                $child->getAttribute('value') === $select )
            {
                $selectedOption = $child;
                break;
            }
        }

        // 選択されたoption要素が存在するか確認
        $this->assertNotNull( $selectedOption );

        // 選択されたoption要素のvalue属性が$selectであるか確認
        $this->assertEquals( $select, $selectedOption->getAttribute('value') );







        // dd($selectElement);
        // dd($selectElement->setAttribute( 'value', $select )); //+name: "value" +value: "credit" or "convenience"
        // dd($selectElement->getAttribute('value')); //"convenience" or "credit" ←選択できてるってこと？
        // dd($selectElement->childNodes[0]->tagName); //"option"
        // dd($selectElement->childNodes[2]->getAttribute('value')); //[0]:"" [1]:"convenience" [2]:"credit"

        // dd($dom->getElementsByTagName('div')->item(1)->getAttribute('id')); //"displayArea"
        // dd($dom->getElementsByTagName('div')->item(1)->textContent); //"" ←選択されていない扱い!!!!!それかJavaScriptが反映してない？
        // dd($dom->getElementsByTagName('div')->item(0)->getAttribute('class')); //display-ttl
        // dd($dom->getElementsByTagName('div')->item(0)->textContent); //"PAYMENT"

        // dd( $dom->getElementById("select")->getAttribute('value') ); //"convenience" or "credit"
        // dd( $dom->getElementById("select")->textContent ); //Chose your payment.\n\nCONVENIENCE STORE\n\nCREDIT CARD\n
        // dd( $selectElement->textContent ); //Chose your payment.\n\nCONVENIENCE STORE\n\nCREDIT CARD\n
        // dd( $selectedOption->textContent ); // CONVENIENCE STORE\n or CREDIT CARD\n
        // dd( $dom->getElementById("displayArea")->textContent ); //"" ←選択されていない扱い？ or 存在しないところを参照してる？
















// ↓新しく定義しているせいかセレクトボックスから選択されておらず、「Chose payment」になっている
        // XPathクエリ(XML文書やHTML文書内の特定の要素や属性を選択するための言語)を実行
        // $xpath = new \DOMXPath($dom);

        // div要素のidにdisplayAreaを持つ要素内のテキストを取得(JavaScriptだからhtmlには文字がない)
//         $elements = $xpath->query('//div[@id="displayArea"]');
// dd($elements[0]->textContent);
//         if ($elements->length > 0) {
//             foreach ($elements as $element) {
//                 echo 'ELEMENT: ' . $element->textContent;
//             }
//         }








    }
}
