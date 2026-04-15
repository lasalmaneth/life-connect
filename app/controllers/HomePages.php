<?php 

namespace App\Controllers;

use App\Core\Controller;

class HomePages {
    use Controller;

    public function education(){
        $this->view('home_pages/education');
    }

    public function legal(){
        $this->view('home_pages/legal');
    }

    public function liveDonation(){
        $this->view('home_pages/live-donation');
    }

    public function deceasedDonation(){
        $this->view('home_pages/deceased-donation');
    }

    public function ourStory(){
        $homeModel = new \App\Models\HomeModel();
        $data['stats'] = $homeModel->getHomepageStats();
        $this->view('home_pages/our-story', $data);
    }

    public function reachUs(){
        $homeModel = new \App\Models\HomeModel();
        $data['stats'] = $homeModel->getHomepageStats();
        $this->view('home_pages/reach-us', $data);
    }

    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('reach-us');
            return;
        }

        $model = new \App\Models\ContactModel();
        
        $data = [
            'full_name' => $_POST['full_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'subject' => $_POST['subject'] ?? 'General Inquiry',
            'message' => $_POST['message'] ?? ''
        ];

        $errors = $model->validate($data);

        if (empty($errors)) {
            $model->insertMessage($data);
            $_SESSION['contact_success'] = "Thank you! Your message has been sent successfully. We will get back to you soon.";
            redirect('reach-us');
        } else {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_data'] = $data;
            redirect('reach-us');
        }
    }

    public function religion(){
        $this->view('home_pages/religion');
    }

    public function faithDetails() {
        $url = $_GET['url'] ?? '';
        $parts = explode('/', $url);
        $id = end($parts);

        $faiths = [
            'buddhism' => [
                'title' => 'Buddhism',
                'id' => 'buddhism',
                'icon' => 'fas fa-dharmachakra',
                'color' => '#ff9800',
                'quote' => 'Giving is the greatest of Buddhist virtues.',
                'summary' => [
                    'Personal choice guided by compassion',
                    'Encourages generosity (dana)',
                    'Focus on reducing suffering'
                ],
                'intro' => 'Buddhism does not strictly require or forbid organ donation. Instead, it is a personal decision guided by compassion, intention, and awareness. Helping others and relieving suffering are central teachings, making organ donation a meaningful expression of kindness.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-om',
                        'content' => 'The body is temporary and subject to change, while consciousness continues beyond death. Because of this, donating organs is often seen as a final act of generosity that benefits others.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-book-open',
                        'content' => 'Buddhist teachings emphasize Compassion (Karuna), Generosity (Dana), and Non-attachment to the body. Giving, even after death, is considered a virtuous act that aligns with the path of reducing suffering.'
                    ]
                ],
                'considerations' => [
                    'The moment of death is spiritually important',
                    'A peaceful passing should not be disturbed',
                    'The donor’s intention matters deeply'
                ],
                'misunderstandings' => [
                    'Buddhism does NOT forbid donation',
                    'The body does NOT need to remain intact',
                    'Donation does NOT harm rebirth'
                ],
                'family' => 'Families should clearly understand the donor’s wishes to ensure they are respected.',
                'care' => 'Body handled with dignity. Religious support can be requested.',
                'faqs' => [
                    ['q' => 'Is organ donation allowed in Buddhism?', 'a' => 'Yes, it is generally accepted as a personal and compassionate choice.'],
                    ['q' => 'Does it affect karma?', 'a' => 'It is often considered positive karma because it helps others.']
                ]
            ],
            'hinduism' => [
                'title' => 'Hinduism',
                'id' => 'hinduism',
                'icon' => 'fas fa-om',
                'color' => '#ff5722',
                'quote' => 'In the joy of others lies our own.',
                'summary' => [
                    'Supports selfless giving (daan)',
                    'Soul is eternal',
                    'Donation seen as noble'
                ],
                'intro' => 'Hinduism strongly supports organ donation as an act of selflessness and service.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-sun',
                        'content' => 'The soul (atman) is eternal, while the body is temporary. Donating organs does not affect the soul’s journey and is seen as a positive action.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-hand-holding-heart',
                        'content' => 'Core beliefs include: Daan (giving), Seva (service), and Karma (action). Giving one’s body to help others is considered one of the highest forms of donation.'
                    ]
                ],
                'considerations' => [
                    'Decision should be made during life',
                    'Must be voluntary',
                    'No major restrictions'
                ],
                'misunderstandings' => [
                    'Donation does NOT affect reincarnation',
                    'Body does NOT need to remain complete',
                    'Religion does NOT forbid donation'
                ],
                'family' => 'Families should be informed and support the donor’s wishes.',
                'care' => 'Cremation rituals continue normally. Body treated respectfully.',
                'faqs' => [
                    ['q' => 'Will donation affect rebirth?', 'a' => 'No, the soul is separate from the body.'],
                    ['q' => 'Can rituals still be done?', 'a' => 'Yes, all last rites can proceed normally.']
                ]
            ],
            'islam' => [
                'title' => 'Islam',
                'id' => 'islam',
                'icon' => 'fas fa-moon',
                'color' => '#4caf50',
                'quote' => 'If anyone saves a life, it is as if he saves all of humankind.',
                'summary' => [
                    'Views differ among scholars',
                    'Saving life is highly valued',
                    'Consent and dignity are essential'
                ],
                'intro' => 'Organ donation in Islam is a personal decision with different interpretations among scholars.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-star-and-crescent',
                        'content' => 'Many scholars allow donation as an act of charity, especially when it saves lives. Others take a more cautious approach due to the sanctity of the body.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-book',
                        'content' => 'Islam emphasizes: Saving life, Respect for the body, and Acting with intention.'
                    ]
                ],
                'considerations' => [
                    'Consent is required',
                    'Some prefer donation after circulatory death',
                    'Body dignity must be preserved'
                ],
                'misunderstandings' => [
                    'Islam does NOT completely forbid donation',
                    'Not all Muslims must donate',
                    'Many scholars DO allow it'
                ],
                'family' => 'Family discussion is important. Consultation with an Imam is recommended.',
                'care' => 'Body treated with dignity. Quick burial respected.',
                'faqs' => [
                    ['q' => 'Is organ donation halal?', 'a' => 'It depends on interpretation, but many scholars allow it.'],
                    ['q' => 'Can I set conditions?', 'a' => 'Yes, you can specify your preferences.']
                ]
            ],
            'christianity' => [
                'title' => 'Christianity',
                'id' => 'christianity',
                'icon' => 'fas fa-cross',
                'color' => '#2196f3',
                'quote' => 'Giving organs is the most generous act of self-giving imaginable.',
                'summary' => [
                    'Act of love and sacrifice',
                    'Strongly supported',
                    'Must be voluntary'
                ],
                'intro' => 'Christianity widely supports organ donation as an act of love, compassion, and service.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-heart',
                        'content' => 'Donation reflects the teachings of Jesus Christ — helping others and showing compassion.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-dove',
                        'content' => 'Core values include: Love, Sacrifice, and Service. Organ donation is often described as a noble and generous act.'
                    ]
                ],
                'considerations' => [
                    'Must be voluntary',
                    'Must not cause harm',
                    'Requires consent'
                ],
                'misunderstandings' => [
                    'Body does NOT need to remain whole',
                    'Donation is NOT sinful'
                ],
                'family' => 'Families should understand and respect the donor’s wishes.',
                'care' => 'Body handled with dignity. Pastoral support available.',
                'faqs' => [
                    ['q' => 'Does Christianity support donation?', 'a' => 'Yes, it aligns with helping others.'],
                    ['q' => 'Does it affect salvation?', 'a' => 'No.']
                ]
            ],
            'sikhism' => [
                'title' => 'Sikhism',
                'id' => 'sikhism',
                'icon' => 'fas fa-khanda',
                'color' => '#ffc107',
                'quote' => 'Saving a human life is one of the greatest acts a person can do.',
                'summary' => [
                    'Strongly encouraged',
                    'Based on selfless service',
                    'Helping humanity'
                ],
                'intro' => 'Sikhism strongly supports organ donation as a form of selfless service.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-infinity',
                        'content' => 'The soul continues after death, and the body is temporary.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-hands',
                        'content' => 'Key concept:<br>• Nishkam Seva (selfless service)<br>• Helping others is a central teaching of Sikhism.'
                    ]
                ],
                'considerations' => [
                    'Personal decision',
                    'Encouraged as service'
                ],
                'misunderstandings' => [
                    'Body does NOT need to remain intact',
                    'Donation does NOT affect the soul'
                ],
                'family' => 'Families should respect and support the decision.',
                'care' => 'Body treated with dignity. Returned quickly for rites.',
                'faqs' => [
                    ['q' => 'Is donation encouraged?', 'a' => 'Yes, strongly.'],
                    ['q' => 'Does it affect spiritual life?', 'a' => 'No.']
                ]
            ],
            'judaism' => [
                'title' => 'Judaism',
                'id' => 'judaism',
                'icon' => 'fas fa-star-of-david',
                'color' => '#0038b8',
                'quote' => 'If you save one life, you save the whole world.',
                'summary' => [
                    'Saving life is highest priority',
                    'Often encouraged',
                    'May require guidance'
                ],
                'intro' => 'Judaism strongly supports organ donation because saving life is a central value.',
                'sections' => [
                    [
                        'title' => 'Faith Perspective',
                        'icon' => 'fas fa-shield-heart',
                        'content' => 'Saving life overrides most other religious concerns.'
                    ],
                    [
                        'title' => 'Religious Teachings',
                        'icon' => 'fas fa-balance-scale',
                        'content' => '• Pikuach nefesh (saving life)<br>• Mitzvah (good deed)'
                    ]
                ],
                'considerations' => [
                    'Some require Rabbi consultation',
                    'Must follow religious law'
                ],
                'misunderstandings' => [
                    'Judaism does NOT forbid donation',
                    'Many support it'
                ],
                'family' => 'Families and Rabbis may guide decisions.',
                'care' => 'Body treated with dignity. Burial respected.',
                'faqs' => [
                    ['q' => 'Is donation allowed?', 'a' => 'Yes, often encouraged.'],
                    ['q' => 'Should I consult a Rabbi?', 'a' => 'Yes, if unsure.']
                ]
            ],
            'other' => [
                'title' => 'Other Beliefs',
                'id' => 'other',
                'icon' => 'fas fa-heart',
                'color' => '#9c27b0',
                'quote' => 'Helping others is a universal human value.',
                'summary' => [
                    'Not limited to religion',
                    'Based on humanity',
                    'Personal choice'
                ],
                'intro' => 'Organ donation is a universal act of kindness chosen by people of all beliefs.',
                'sections' => [
                    [
                        'title' => 'Perspective',
                        'icon' => 'fas fa-globe',
                        'content' => 'Supported by: Compassion, Ethics, and Humanity.'
                    ]
                ],
                'considerations' => [],
                'misunderstandings' => [],
                'family' => 'Families are encouraged to discuss donation as a shared value of humanitarian service.',
                'care' => 'Every person is treated with profound respect and dignity, honoring their personal ethics and final contribution to humanity.',
                'faqs' => [
                    ['q' => 'Do I need religion to donate?', 'a' => 'No.'],
                    ['q' => 'Can I still make a meaningful choice?', 'a' => 'Yes.']
                ]
            ]
        ];

        if (!isset($faiths[$id])) {
            $this->view('_404');
            return;
        }

        $data['faith'] = $faiths[$id];
        $this->view('home_pages/religions/details', $data);
    }

    public function donors(){
        $this->view('home_pages/donors');
    }

    public function aftercareGuide(){
        $this->view('home_pages/aftercare-guide');
    }

    public function custodians(){
        $this->view('home_pages/custodians');
    }
}
