<?php
 
class PageTableSeeder extends Seeder {
 
	public function run()
	{
		DB::table('pages')->truncate();


		Page::create(array(
			'user_id' => 1,
			'book_id' => 1,
			'parent_id' => 0,
			'number' => 1,
			'content' => "“Gnourf”, said Arrgh. \n \nThis was the first use of language to express something immaterial, the beginning of a form of thought. Arrgh was referring Arrgh, this human being. Atre was referring to Fire, yet an other material object. Gnourf was expressing disapprobation. A conceptual thing.",
			'status' => true
		));

		Page::create(array(
			'user_id' => 2,
			'book_id' => 1,
			'parent_id' => 1,
			'number' => 2,
			'content' => "This is an alternate page 2 by user 2",
			'status' => true
		));

		Page::create(array(
			'user_id' => 3,
			'book_id' => 1,
			'parent_id' => 1,
			'number' => 2,
			'content' => "This is an alternate page 2 by user 3",
			'status' => false
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 2,
			'parent_id' => 0,
			'number' => 1,
			'content' => "In faciem versus Amphitruonis Iuppiter, \ndum bellum gereret cum Telobois hostibus, \nAlcmenam uxorem cepit usurariam. \nMercurius formam Sosiae servi gerit \nabsentis: his Alcmena decipitur dolis. \npostquam rediere veri Amphitruo et Sosia, \nuterque deluduntur in mirum modum. \nhinc iurgium, tumultus uxori et viro, \ndonec cum tonitru voce missa ex aethere \nadulterum se Iuppiter confessus est. \n",
			'status' => true
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 2,
			'parent_id' => 4,
			'number' => 2,
			'content' => "In faciem versus Amphitruonis Iuppiter, \ndum bellum gereret cum Telobois hostibus, \nAlcmenam uxorem cepit usurariam. \nMercurius formam Sosiae servi gerit \nabsentis: his Alcmena decipitur dolis. \npostquam rediere veri Amphitruo et Sosia, \nuterque deluduntur in mirum modum. \nhinc iurgium, tumultus uxori et viro, \ndonec cum tonitru voce missa ex aethere \nadulterum se Iuppiter confessus est. \n",
			'status' => true
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 2,
			'parent_id' => 4,
			'number' => 2,
			'content' => "*A*more captus Alcumenas Iuppiter \n*M*utavit sese in formam eius coniugis, \n*P*ro patria Amphitruo dum decernit cum hostibus. \n*H*abitu Mercurius ei subservit Sosiae. \n*I*s advenientis servum ac dominum frustra habet. \n*T*urbas uxori ciet Amphitruo, atque invicem \n*R*aptant pro moechis. Blepharo captus arbiter \n*V*ter sit non quit Amphitruo decernere. \n*O*mnem rem noscunt. geminos Alcumena enititur \n",
			'status' => true
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 3,
			'parent_id' => 0,
			'number' => 1,
			'content' => "MERCURY, _a god._ \nSOSIA, _slave of Amphitryon._ \nJUPITER, _a god._ \nALCMENA, _wife of Amphitryon._ \nAMPHITRYON, _commander-in-chief of the Theban army._ \nBLEPHARO, _a pilot._ \nBROMIA, _maid to Alcmena._ \n\n _Scene:--Thebes. A street before Amphitryon’s house._\n\nPROLOGUE\n\nSPOKEN BY THE GOD MERCURY\n\nAccording as ye here assembled would have me prosper you and bring you luck in your buyings and in your sellings of goods, yea, and forward you in all things; and according as ye all would have me find your business affairs and speculations happy outcome in foreign lands and here at home, and crown your present and future undertakings with fine, fat profits for evermore;\n\nand according as ye would have me bring you and all yours glad news, reporting and announcing matters which most contribute to your common good (for ye doubtless are aware ere now that ’tis to me the other gods have yielded and granted plenipotence o’er messages and profits); according as ye would have me bless you in these things, then in such degree will ye (_suddenly dropping his pomposity_) keep still while we are acting this play and all be fair and square judges of the performance. \n\n",
			'status' => true
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 3,
			'parent_id' => 7,
			'number' => 2,
			'content' => "Now I will tell you who bade me come, and why I came, and likewise myself state my own name. Jupiter bade me come: my name is Mercury (_pauses, evidently hoping he has made an impression_). My father has sent me here to you to make a plea, yea, albeit he knew that whatever was told you in way of command you would do, inasmuch as he realized that you revere and dread him as men should Jupiter. ",
			'status' => false
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 3,
			'parent_id' => 8,
			'number' => 3,
			'content' => "But the fact remains that he has bidden me make this request in suppliant wise, with gentle, kindly words. (_confidentially_) For you see, that Jupiter that “bade me come here” is just like any one of you in his horror of (_rubbing his shoulders reflectively_) trouble[A]: his mother being human, also his father, it should not seem strange if he does feel apprehensive regarding himself. ",
			'status' => true
		));

		Page::create(array(
			'user_id' => 5,
			'book_id' => 3,
			'parent_id' => 9,
			'number' => 4,
			'content' => "Yes, and the same is true of me, the son of Jupiter: once my father has some trouble I am afraid I shall catch it, too. (_rather pompously again_) Wherefore I come in peace and peace do I bring to you. It is a just and trifling request I wish you to grant: for I am sent as a just pleader pleading with the just for what is just. ",
			'status' => true
		));

	}
 
}


