<?php

use Phinx\Seed\AbstractSeed;

class PagesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'About Us',
                'slug' => 'about-us',
                'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>',
                'is_active' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'page_content' => 'Lorem ipsum dolor sit amet, mel et noster commune disputando, no nec suas vocibus, mel veri assueverit eu. Quo wisi vituperatoribus at, quo no vero blandit adipisci. Eam sanctus aliquando ad. Per in summo detracto. No accusamus hendrerit eum.

Id mei mutat aperiri, magna iusto essent eam in, in nam modus nonumy laoreet. Qui an autem similique, pri ea illum libris vivendum, nibh noluisse corrumpit ei mei. Te mea consul molestie ullamcorper, ut iusto aliquam duo, an quo putent incorrupte. Pri vide volumus an, vel in mollis pertinax expetenda.

Ne vix detraxit maluisset, augue dicta liberavisse cu pri. Ne vel harum discere saperet, id vel tacimates mediocrem. Summo repudiare dissentiunt pri at, dicam salutandi definitionem mel ne. Pro erat fastidii ponderum ut, solum audire mentitum at quo. Adhuc voluptatum comprehensam no pro.',
                'is_active' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'Press',
                'slug' => 'press',
                'page_content' => 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.

Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.

Lorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.

When computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.

They use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.',
                'is_active' => 1
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'How it Works',
                'slug' => 'how-it-works',
                'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>',
                'is_active' => 1
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'Help',
                'slug' => 'help',
                'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>',
                'is_active' => 1
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'FAQ',
                'slug' => 'faq',
                'page_content' => 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.

Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.

Lorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.

When computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.

They use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.',
                'is_active' => 0
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'language_id' => 42,
                'title' => 'Terms and conditions',
                'slug' => 'term-and-conditions',
                'page_content' => 'Lorem ipsum dolor sit amet, mel et noster commune disputando, no nec suas vocibus, mel veri assueverit eu. Quo wisi vituperatoribus at, quo no vero blandit adipisci. Eam sanctus aliquando ad. Per in summo detracto. No accusamus hendrerit eum.

Id mei mutat aperiri, magna iusto essent eam in, in nam modus nonumy laoreet. Qui an autem similique, pri ea illum libris vivendum, nibh noluisse corrumpit ei mei. Te mea consul molestie ullamcorper, ut iusto aliquam duo, an quo putent incorrupte. Pri vide volumus an, vel in mollis pertinax expetenda.

Ne vix detraxit maluisset, augue dicta liberavisse cu pri. Ne vel harum discere saperet, id vel tacimates mediocrem. Summo repudiare dissentiunt pri at, dicam salutandi definitionem mel ne. Pro erat fastidii ponderum ut, solum audire mentitum at quo. Adhuc voluptatum comprehensam no pro.',
                'is_active' => 0
            ]
        ];

        $roles = $this->table('pages');
        $roles->insert($data)
              ->save();
    }
}
