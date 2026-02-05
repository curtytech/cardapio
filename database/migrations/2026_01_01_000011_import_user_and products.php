<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            <<<'SQL'
                INSERT INTO users
                (id, name, image_logo, image_banner, slug, celphone, zipcode, address, neighborhood, city, "number", state, complement, instagram, facebook, whatsapp, email, color_primary, color_secondary, email_verified_at, password, "role", remember_token, created_at, updated_at)
                VALUES(21, 'You Burger', 'logos/01KAET0G350G5W9VVJMQ09VVZQ.jpg', 'banners/01KAA7SHB3Z7V36HRS6GFX291S.png', 'youburger', '(21) 99999-9999', '04047-002', 'Rua Pedro de Toledo', 'Vila Clementino', 'São Paulo', '1287', 'São Paulo', 'Galpão 2 (entrada lateral)', 'phelipecurty', 'https://www.facebook.com/phelipe.curty', '(21) 99999-9999', 'youburger@gmail.com', '#fc7d08', '#f0a11a', NULL, '$2y$12$/OpDU6Q0OGdVH0/Auv/BOetbw0heRByZBG4qX/kjdtAWXPeLHRK6y', 'user', 'mTYbotuyhRd4pdWtOyNvtZE9pg2FlkuzHht8M85OKNXlhPpO2zVpb75tuziV', '2025-11-14 14:09:45', '2025-11-26 19:08:27');
                INSERT INTO users
                (id, name, image_logo, image_banner, slug, celphone, zipcode, address, neighborhood, city, "number", state, complement, instagram, facebook, whatsapp, email, color_primary, color_secondary, email_verified_at, password, "role", remember_token, created_at, updated_at)
                VALUES(25, 'YouPizza', 'logos/01KC2BV4V8GHCR0H1WMM3JZZ0T.jpg', 'banners/01KC2BV4VAAXG8DCB25DEZNW0J.avif', 'youpizza', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'phelipecurty', NULL, NULL, 'curtytech@gmail.com', '#000000', '#000000', NULL, '$2y$12$NuDmiZkzSA7JOn6yep.i.eafscpmyo11UCfUJTkoHGgvZ8vUKTOAy', 'user', NULL, '2025-12-09 20:10:35', '2025-12-09 20:11:31');
                INSERT INTO users
                (id, name, image_logo, image_banner, slug, celphone, zipcode, address, neighborhood, city, "number", state, complement, instagram, facebook, whatsapp, email, color_primary, color_secondary, email_verified_at, password, "role", remember_token, created_at, updated_at)
                VALUES(26, 'Cantina Vila Inca', 'logos/01KD8Z9MY2WQQTT47DFEYYXYTX.jpeg', 'banners/01KD8Z9MY4FR9KTHTCR90K6QW1.jpeg', 'cantinavilainca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'marcelocurty@gmail.com', '#3B82F6', '#1E40AF', NULL, '$2y$12$tbZyHyfyczU9m7SHypl6UO8Z//ajgJyqS8/sVZAu3pqZNE8JEL3fG', 'user', NULL, '2025-12-22 00:14:36', '2025-12-24 20:02:38');
                INSERT INTO users
                (id, name, image_logo, image_banner, slug, celphone, zipcode, address, neighborhood, city, "number", state, complement, instagram, facebook, whatsapp, email, color_primary, color_secondary, email_verified_at, password, "role", remember_token, created_at, updated_at)
                VALUES(27, 'Carioca', 'logos/01KG314MMX546XGKA46C7JPRKG.png', 'banners/01KG314MN94J53QBB08ZNG3P3G.png', 'carioca', '(21) 96413-0918', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://www.instagram.com/saborcariocamage/', NULL, '(21) 96413-0918', 'carioca@gmail.com', '#ff0000', '#8B5CF6', NULL, '$2y$12$JjvIgSdY7M0sm4.29yHGrOxMC0gDIPEAc.gS0dY6jD/7MWCYyCAiq', 'user', NULL, '2026-01-28 19:21:53', '2026-01-28 19:33:46');
                INSERT INTO users
                (id, name, image_logo, image_banner, slug, celphone, zipcode, address, neighborhood, city, "number", state, complement, instagram, facebook, whatsapp, email, color_primary, color_secondary, email_verified_at, password, "role", remember_token, created_at, updated_at)
                VALUES(28, 'Gabriel', NULL, NULL, 'gabriel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'contato.legalizar@gmail.com', '#0000FF', '#8B5CF6', NULL, '$2y$12$1R1BHIr/u1MWWMbXYlwKs..N1sngIoTRCFsUdzgbcHSCRAq6TBXbS', 'user', NULL, '2026-02-02 16:48:30', '2026-02-02 16:48:30');
                    
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(47, 28, 'BEBIDAS', 'BEBIDAS', '#3B82F6', 1, '2026-02-03 17:20:10', '2026-02-03 17:20:10', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(46, 27, 'Bebidas', 'As melhores bebidas da região. 
                Perfeitas para um dia quente ou para se refrescar com sabores frescos e saudáveis.', '#3B82F6', 1, '2026-01-28 19:32:05', '2026-01-28 19:32:05', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(44, 26, 'Salgados', 'Confira nossos salgados', '#e6b217', 1, '2025-12-22 00:15:03', '2025-12-22 00:15:26', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(45, 26, 'Bebidas', 'Bebidas', '#3B82F6', 1, '2025-12-24 20:03:18', '2025-12-24 20:03:18', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(43, 25, 'Pizza', 'As melhores pizzas da região', '#f53b3b', 1, '2025-12-09 20:12:23', '2025-12-09 20:12:23', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(29, 21, 'Clássicos da Casa', 'Hambúrgueres tradicionais com aquele sabor que nunca falha.
                Receitas simples, suculentas e com o gostinho de hamburgueria raiz.', '#C57B00', 1, '2025-11-14 14:11:43', '2025-11-17 18:09:40', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(34, 21, 'Brasa & Defumados', 'Sabores intensos com toque rústico e aroma marcante de defumação.
                Carnes suculentas, especiarias e molhos defumados irresistíveis.', '#8B4513', 1, '2025-11-17 18:12:20', '2025-11-17 18:12:20', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(36, 21, 'Veg & Green (Vegetarianos e Veganos)', 'Alternativas sem carne com muito sabor e criatividade.
                Ingredientes naturais, texturas surpreendentes e combinações inteligentes.', '#A3B18A', 1, '2025-11-17 18:13:50', '2025-11-17 18:14:41', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(37, 21, 'Frango & Crispy Lovers', 'Crocância máxima, frango bem temperado e combinações de lamber os dedos.
                Chamado perfeito para os apaixonados por frango empanado ou grelhado.', '#E0C7A5', 1, '2025-11-17 18:14:10', '2025-11-17 18:14:10', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(38, 21, 'Bebidas', 'Bebidas sempre na temperatura ideal para acompanhar o melhor hambúrguer da cidade.', '#4DB7FF', 1, '2025-11-17 20:11:39', '2025-11-17 20:11:39', NULL);
                INSERT INTO categories
                (id, user_id, name, description, color, is_active, created_at, updated_at, slug)
                VALUES(42, 21, 'aaaaa', 'asdasdas', '#3B82F6', 1, '2025-12-09 14:47:17', '2025-12-09 14:47:17', NULL);

                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(131, 21, 29, NULL, 'Cheese Simples', '<p>&nbsp;Hambúrguer bovino de 150 g, dois queijos (mussarela + cheddar), cebola roxa, ketchup e picles.&nbsp;</p>', 'products/01KAA3XR9SBC4N3TEY0TGDX3Q0.png', 'active', 30, '["Tradicional","Mais Vendido"]', '2025-11-14 14:15:34', '2025-11-17 23:55:40');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(133, 21, 29, NULL, 'Clássico Tradicional', '<p>&nbsp;Hambúrguer de carne bovina suculenta, queijo derretido, alface crocante, tomate fresco e maionese especial no pão brioche.&nbsp;</p>', 'products/01KAA5E5BVXFFTV3ETB0J39CJX.png', 'active', 28, '["Tradicional","Mais Vendido"]', '2025-11-17 18:18:16', '2025-11-18 00:22:06');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(134, 21, 29, NULL, 'Raiz Smash', '<p>&nbsp;Hambúrguer smash fininho e bem grelhado, queijo prato, cebola tostada e picles em pão macio.&nbsp;</p>', 'products/01KAA602B4KYYCAJ144E0RAZJD.png', 'active', 26, '["Tradicional","Promocional"]', '2025-11-17 18:20:53', '2025-11-18 00:31:53');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(138, 21, 34, NULL, 'Smokey Texas', '<p>&nbsp;Hambúrguer defumado no lenho, queijo cheddar, tiras de bacon e molho barbecue defumado.&nbsp;</p>', 'products/01KAA1BB1SQNBPSFYS4ARVA22H.png', 'active', 44, '["tradicional","promocional"]', '2025-11-17 18:23:59', '2025-11-17 23:10:39');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(139, 21, 34, NULL, 'Lenha Rústica', '<p>&nbsp;Hambúrguer de carne maturada, cebola grelhada no carvão, queijo suíço e maionese defumada.&nbsp;</p>', 'products/01KAA19R6NW2Z4SCG7TE7TCWB7.png', 'active', 46, '["gourmet","artesanal"]', '2025-11-17 18:24:36', '2025-11-17 23:09:47');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(140, 21, 34, NULL, 'Barbecue do Pomar', '<p>&nbsp;Hambúrguer grelhado, molho barbecue caseiro com maçã defumada, cogumelos e alface.&nbsp;</p>', 'products/01KAA14VDSH715XJRVZG98761K.png', 'active', 43, '["mais vendido","tradicional"]', '2025-11-17 18:25:14', '2025-11-17 23:07:07');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(144, 21, 36, NULL, 'Verde Poder', '<p>&nbsp;Hambúrguer vegetal de grão-de-bico e ervilha, folhas verdes, tomate, cebola roxa e molho vegano de tahine.&nbsp;</p>', 'products/01KAA6J8TCX14M5DAQK0G7G3SR.png', 'active', 38, '["vegetariano","vegano","org\u00e2nico"]', '2025-11-17 18:28:50', '2025-11-18 00:41:49');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(145, 21, 36, NULL, 'Cogumelo Mágico', '<p>&nbsp;Hambúrguer de cogumelos (shitake e portobello), queijo vegano, rúcula, pesto de manjericão.&nbsp;</p>', 'products/01KAA6VVRGT11CKXTPEHP1FBME.png', 'active', 40, '["vegano","artesanal","low carb"]', '2025-11-17 18:29:37', '2025-11-18 00:47:03');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(146, 21, 36, NULL, 'Plant Monster', '<p>&nbsp;Hambúrguer à base de lentilha e beterraba, cebola caramelizada, picles e maionese vegana de alho.&nbsp;</p>', 'products/01KAA6QSN5NVG5KR086QCH83J5.png', 'active', 42, '["vegano","mais vendido","fitness"]', '2025-11-17 18:30:23', '2025-11-18 00:44:50');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(147, 21, 38, NULL, 'Água Mineral ', '<p>500ml</p>', 'products/01KA9VVCH21Y8MJ2852YRQC1MC.webp', 'active', 6.9, '["bebidas"]', '2025-11-17 20:12:49', '2025-11-17 21:34:34');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(148, 21, 38, NULL, 'Coca-Cola', '<p>&nbsp;350ml&nbsp;</p>', 'products/01KA9ZAF99KBM0GMBQH1JE15Z9.png', 'active', 6.9, '["bebida"]', '2025-11-17 20:14:54', '2025-11-17 22:35:14');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(149, 21, 38, NULL, 'Pepsi', '<p> 350ml&nbsp;</p>', 'products/01KAA00RYNR8EY2A0QFXXW94S5.png', 'active', 6.5, '["bebida"]', '2025-11-17 20:16:01', '2025-11-17 22:47:24');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(150, 25, 43, NULL, 'Pizza de Calabresa', '<p>Pizza de Calabresa</p>', 'products/01KC2BYCNGM0E9XJ9KRY3YT0SA.webp', 'active', 50, '["Artesanal","Tradicional"]', '2025-12-09 20:13:17', '2025-12-09 20:13:17');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(151, 25, 43, NULL, 'Pizza de Peperoni', '<p>Pizza de Peperoni</p>', 'products/01KC2BZSNQK8NJSHN2040KR4ZD.webp', 'active', 60, '["Novo","Artesanal"]', '2025-12-09 20:14:03', '2025-12-09 20:14:03');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(152, 26, 44, NULL, 'Salgados Variádos', '<ul><li>Risole de Frango</li><li>Kibe&nbsp;</li><li>Coxinha</li><li>Pasteis</li></ul>', 'products/01KD1PP24HZ3K14PAMG0286GVQ.jpg', 'active', 5, '["Tradicional"]', '2025-12-22 00:17:26', '2025-12-22 00:17:26');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(153, 26, 45, NULL, 'Refrigerante', '<p>Refrigerante</p>', 'products/01KD8ZFEVPTFDCR6CKBHJ8TF3Q.png', 'active', 3, '[]', '2025-12-24 20:05:48', '2025-12-24 20:05:48');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(154, 27, 46, NULL, 'Suco ', '<p>&nbsp;Sucos naturais de vários sabores leves e saudáveis com ótimas avaliações.</p>', 'products/01KG322ZWAGEW6BEZ3JW4BGDKV.png', 'active', 10, '["Fitness","Low Carb","Promocional","Natural"]', '2026-01-28 19:38:21', '2026-01-28 19:43:04');
                INSERT INTO products
                (id, user_id, category_id, barcode, name, description, image, status, sell_price, features, created_at, updated_at)
                VALUES(155, 28, 47, NULL, 'coca', '<p>coca</p>', NULL, 'active', 8, '[]', '2026-02-03 17:20:48', '2026-02-03 17:20:48');
            SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
