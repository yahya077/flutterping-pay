<?php

namespace yahya077\FlutterpingPay\Pages\Payment\Widgets;

use Flutterping\Models\Widget;
use Flutterping\Resources\Action\UpdateNotifierAction;
use Flutterping\Resources\Core\CoreDouble;
use Flutterping\Resources\Event\ActionEvent;
use Flutterping\Resources\Foundation\ScrollController;
use Flutterping\Resources\Json;
use Flutterping\Resources\Material\InputDecoration;
use Flutterping\Resources\Material\OutlineInputBorder;
use Flutterping\Resources\Paintings\Border;
use Flutterping\Resources\Paintings\BorderRadius;
use Flutterping\Resources\Paintings\BorderSide;
use Flutterping\Resources\Paintings\BoxDecoration;
use Flutterping\Resources\Paintings\EdgeInsetsPadding;
use Flutterping\Resources\Paintings\TextStyle;
use Flutterping\Resources\Renderings\CrossAxisAlignment;
use Flutterping\Resources\Renderings\MainAxisAlignment;
use Flutterping\Resources\Renderings\TextInputAction;
use Flutterping\Resources\UI\Color;
use Flutterping\Resources\UI\FontWeight;
use Flutterping\Resources\UI\TextInputType;
use Flutterping\Resources\UI\TextOverflow;
use Flutterping\Resources\Validator\ComposeValidator;
use Flutterping\Resources\Validator\MaxLengthValidator;
use Flutterping\Resources\Validator\MinLengthValidator;
use Flutterping\Resources\Validator\RangeValidator;
use Flutterping\Resources\Validator\RegexValidator;
use Flutterping\Resources\Validator\RequiredValidator;
use Flutterping\Resources\Value\DynamicValue;
use Flutterping\Resources\Value\EvalValue;
use Flutterping\Resources\Value\NotifierValue;
use Flutterping\Resources\Widgets\AppBar as AppBarWidget;
use Flutterping\Resources\Widgets\BottomAppBar;
use Flutterping\Resources\Widgets\CheckboxFormField;
use Flutterping\Resources\Widgets\Column;
use Flutterping\Resources\Widgets\Container;
use Flutterping\Resources\Widgets\Expanded;
use Flutterping\Resources\Widgets\Form;
use Flutterping\Resources\Widgets\GestureDetector;
use Flutterping\Resources\Widgets\IntrinsicHeight;
use Flutterping\Resources\Widgets\RadioGroupFormField;
use Flutterping\Resources\Widgets\RadioListTile;
use Flutterping\Resources\Widgets\Row;
use Flutterping\Resources\Widgets\Scaffold;
use Flutterping\Resources\Widgets\SingleChildScrollView;
use Flutterping\Resources\Widgets\SizedBox;
use Flutterping\Resources\Widgets\Text;
use Flutterping\Resources\Widgets\TextFormField;
use Flutterping\Resources\Widgets\ValueListenableBuilder;
use Flutterping\Resources\Widgets\Visibility;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use yahya077\FlutterpingPay\Pages\Payment\PaymentPage;
use yahya077\FlutterpingPay\Pages\Payment\States\StartPaymentState;

class PaymentDetailWidget extends Widget
{
    protected ScrollController $scrollController;

    protected Authenticatable $user;

    public function __construct()
    {
        $this->scrollController = new ScrollController('paymentPageScrollController');
        $this->user = auth()->user();
    }

    public function getStoredCards(): Collection
    {
        return new Collection;
    }

    public function getAppBar(): Json
    {
        return (new AppBarWidget)
            ->setTitle((new Text(config('flutterping-pay.title'))))
            ->setBackgroundColor(new Color(240, 240, 240, 255));
    }

    protected function widget(): Json
    {
        return (new Scaffold)
            ->setAppBar($this->getAppBar())
            ->setBackgroundColor(new Color(240, 240, 240, 255))
            ->setBody((new SingleChildScrollView)
                ->setChild((new Form(sprintf('%s.paymentForm', config('flutterping-pay.page.routeStateId'))))->setWidget(
                    (new Column)
                        ->setCrossAxisAlignment(CrossAxisAlignment::start())
                        ->setChildren([
                            (new SizedBox(height: new CoreDouble(10))),
                            $this->getAddressWidget(),
                            (new SizedBox(height: new CoreDouble(10))),
                            (new Container)
                                ->setWidth(CoreDouble::infinity())
                                ->setPadding(EdgeInsetsPadding::fromLTRB(left: 15, right: 15, bottom: 15))
                                ->setMargin(EdgeInsetsPadding::fromSymmetric(horizontal: 15))
                                ->setDecoration((new BoxDecoration)
                                    ->setBorderRadius(BorderRadius::all(5))
                                    ->setBorder(Border::all(Color::fromRGB(210, 210, 210), 1))
                                    ->setColor(Color::fromRGB(255, 255, 255))
                                )
                                ->setChild((new Column)
                                    ->setChildren([
                                        (new Row)
                                            ->setMainAxisAlignment(MainAxisAlignment::spaceBetween())
                                            ->setCrossAxisAlignment(CrossAxisAlignment::center())
                                            ->setChildren([
                                                (new Container)
                                                    ->setPadding(EdgeInsetsPadding::fromLTRB(top: 15))
                                                    ->setChild(
                                                        (new Text('Kart Bilgileri'))
                                                            ->setStyle((new TextStyle)
                                                                ->setFontSize(17)
                                                                ->setFontWeight(FontWeight::w600())),
                                                    ),
                                                $this->getStoredCards()->count() > 0 ? (new Visibility)
                                                    ->setVisible(new DynamicValue($this->getStoredCards()->count() > 0))
                                                    ->setChild(
                                                        (new Container)
                                                            ->setWidth(CoreDouble::from(175))
                                                            ->setHeight(CoreDouble::from(40))
                                                            ->setChild(
                                                                (new CheckboxFormField)
                                                                    ->setName('with_selected_card')
                                                                    ->setInitialValue(new DynamicValue($this->getStoredCards()->count() > 0))
                                                                    ->setTitle(
                                                                        (new ValueListenableBuilder)
                                                                            ->setValueListenable(new NotifierValue(sprintf('%s_isMyCardsVisible', config('flutterping-pay.page.routeStateId'))))
                                                                            ->setScopeId('CardSelectVisibilityNotifier')
                                                                            ->setChild((new Visibility)
                                                                                ->setVisible((new EvalValue(sprintf('${ScopeValue.CardSelectVisibilityNotifier.value} == %s', $this->getStoredCards()->count() > 0 ? 'true' : 'false'))))
                                                                                ->setChild(
                                                                                    new Text('Başka Kartla öde')
                                                                                )
                                                                                ->setElseChild(
                                                                                    (new Text('Kayıtlı Kartla öde'))
                                                                                )
                                                                            )
                                                                    )
                                                                    ->setSide(BorderSide::none())
                                                                    ->setOnChanged(
                                                                        (new ActionEvent)
                                                                            ->setStateId('mainStackStateId')
                                                                            ->setAction((new UpdateNotifierAction)
                                                                                ->setNotifierId(sprintf('%s_isMyCardsVisible', config('flutterping-pay.page.routeStateId')))
                                                                                ->setValue(new EvalValue(sprintf('!${NotifierValue.%s_isMyCardsVisible}', config('flutterping-pay.page.routeStateId'))))
                                                                            )
                                                                    )
                                                            )
                                                    ) : (new SizedBox),
                                            ]),
                                        (new SizedBox(height: new CoreDouble(10))),
                                        (new Text('Kartınızı kaydederek bir sonraki alışverişlerinizde hızlıca ödeme yapabilirsiniz.'))
                                            ->setOverflow(TextOverflow::ellipsis())
                                            ->setMaxLines(5)
                                            ->setSoftWrap(true)
                                            ->setStyle((new TextStyle)
                                                ->setColor(Color::fromRGB(120, 120, 120))
                                                ->setFontSize(14)),
                                        (new ValueListenableBuilder)
                                            ->setValueListenable(new NotifierValue(sprintf('%s_isMyCardsVisible', config('flutterping-pay.page.routeStateId'))))
                                            ->setScopeId('CardSelectVisibilityNotifier')
                                            ->setChild((new Visibility)
                                                ->setVisible((new EvalValue(sprintf('${ScopeValue.CardSelectVisibilityNotifier.value} == %s', $this->getStoredCards()->count() > 0 ? 'true' : 'false'))))
                                                ->setChild($this->getStoredCardsWidget())),
                                        (new ValueListenableBuilder)
                                            ->setValueListenable(new NotifierValue(sprintf('%s_isMyCardsVisible', config('flutterping-pay.page.routeStateId'))))
                                            ->setScopeId('CreditCardFormVisibilityNotifier')
                                            ->setChild((new Visibility)
                                                ->setVisible((new EvalValue(sprintf('${ScopeValue.CreditCardFormVisibilityNotifier.value} == %s', $this->getStoredCards()->count() > 0 ? 'false' : 'true'))))
                                                ->setChild((new Container)
                                                    ->setChild((new Column)->setChildren([
                                                        (new TextFormField('card_number', 'paymentForm'))
                                                            ->setKeyboardType(TextInputType::number())
                                                            ->setValidator((new ComposeValidator([
                                                                (new RequiredValidator)->setErrorMessage('Lütfen kart numarası girin'),
                                                            ])))
                                                            ->setTextInputAction(TextInputAction::next())
                                                            ->setDecoration((new InputDecoration)
                                                                ->setFilled(true)
                                                                ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                ->setHintText('Kart Numarası')
                                                                ->setHintStyle((new TextStyle)
                                                                    ->setColor(Color::fromRGB(180, 180, 180))
                                                                    ->setFontSize(14))
                                                            )
                                                            ->setStyle((new TextStyle)
                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                ->setFontSize(14)),
                                                        (new SizedBox(height: new CoreDouble(10))),
                                                        (new TextFormField('card_holder_name', 'paymentForm'))
                                                            ->setValidator((new ComposeValidator([
                                                                (new RegexValidator("^\s*[a-zA-ZğüşöçİĞÜŞÖÇ]+(?:\s+[a-zA-ZğüşöçİĞÜŞÖÇ]+)+\s*$"))->setErrorMessage('Lütfen isim soyisim girin'),
                                                                (new MinLengthValidator(4))->setErrorMessage('Lütfen isim soyisim girin')->withoutSpaces(),
                                                                (new MaxLengthValidator(30))->setErrorMessage('Lütfen isim soyisim girin'),
                                                            ])))
                                                            ->setDecoration((new InputDecoration)
                                                                ->setFilled(true)
                                                                ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                ->setHintText('Kart Üzerindeki İsim')
                                                                ->setHintStyle((new TextStyle)
                                                                    ->setColor(Color::fromRGB(180, 180, 180))
                                                                    ->setFontSize(14))
                                                            )
                                                            ->setStyle((new TextStyle)
                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                ->setFontSize(14)),
                                                        (new SizedBox(height: new CoreDouble(10))),
                                                        (new Row)
                                                            ->setChildren([
                                                                (new Expanded)
                                                                    ->setChild((new TextFormField('card_expire_month', 'paymentForm'))
                                                                        ->setKeyboardType(TextInputType::number())
                                                                        ->setValidator((new ComposeValidator([
                                                                            (new MinLengthValidator(2))->setErrorMessage('örn. 01'),
                                                                            (new RangeValidator(1, 31))->setErrorMessage('Geçersiz Ay'),
                                                                        ])))
                                                                        ->setMaxLength(2)
                                                                        ->setDecoration((new InputDecoration)
                                                                            ->setFilled(true)
                                                                            ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                            ->setHintText('Ay (01)')
                                                                            ->setHintStyle((new TextStyle)
                                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                                ->setFontSize(14))
                                                                        )
                                                                        ->setStyle((new TextStyle)
                                                                            ->setColor(Color::fromRGB(180, 180, 180))
                                                                            ->setFontSize(14))),
                                                                (new SizedBox(width: new CoreDouble(3))),
                                                                (new Expanded)
                                                                    ->setChild((new TextFormField('card_expire_year', 'paymentForm'))
                                                                        ->setKeyboardType(TextInputType::number())
                                                                        ->setValidator((new ComposeValidator([
                                                                            (new MinLengthValidator(2))->setErrorMessage('örn. 25'),
                                                                            (new RangeValidator(25, 40))->setErrorMessage('Geçersiz yıl'),
                                                                        ])))
                                                                        ->setMaxLength(2)
                                                                        ->setDecoration((new InputDecoration)
                                                                            ->setFilled(true)
                                                                            ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                            ->setHintText('Yıl (25)')
                                                                            ->setHintStyle((new TextStyle)
                                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                                ->setFontSize(14))
                                                                        )
                                                                        ->setStyle((new TextStyle)
                                                                            ->setColor(Color::fromRGB(180, 180, 180))
                                                                            ->setFontSize(14))),
                                                                (new SizedBox(width: new CoreDouble(10))),
                                                                (new Expanded)
                                                                    ->setChild((new TextFormField('card_cvc', 'paymentForm'))
                                                                        ->setKeyboardType(TextInputType::number())
                                                                        ->setValidator((new ComposeValidator([
                                                                            (new MinLengthValidator(3))->setErrorMessage('örn. 000'),
                                                                            (new MaxLengthValidator(4))->setErrorMessage('örn. 000'),
                                                                        ])))
                                                                        ->setMaxLength(4)
                                                                        ->setTextInputAction(TextInputAction::done())
                                                                        ->setDecoration((new InputDecoration)
                                                                            ->setFilled(true)
                                                                            ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                            ->setHintText('CVC (000)')
                                                                            ->setHintStyle((new TextStyle)
                                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                                ->setFontSize(14))
                                                                        )
                                                                        ->setStyle((new TextStyle)
                                                                            ->setColor(Color::fromRGB(180, 180, 180))
                                                                            ->setFontSize(14))),
                                                            ]),
                                                        (new SizedBox(height: new CoreDouble(10))),
                                                        $this->getBottomInformationWidget(),
                                                        (new SizedBox(height: new CoreDouble(10))),
                                                        (new TextFormField('card_alias', 'paymentForm'))
                                                            ->setValidator((new ComposeValidator([
                                                                (new MaxLengthValidator(10))->setErrorMessage('10 karakterden fazla olamaz'),
                                                            ])))
                                                            ->setMaxLength(10)
                                                            ->setDecoration((new InputDecoration)
                                                                ->setFilled(true)
                                                                ->setFillColor(Color::fromRGB(255, 255, 255))
                                                                ->setHintText('Takma isim (Şirket, Benim vb.)')
                                                                ->setHintStyle((new TextStyle)
                                                                    ->setColor(Color::fromRGB(180, 180, 180))
                                                                    ->setFontSize(14))
                                                            )
                                                            ->setStyle((new TextStyle)
                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                ->setFontSize(14)),
                                                    ])))),
                                    ])
                                ),
                            (new SizedBox(height: new CoreDouble(10))),
                            (new Container)
                                ->setWidth(CoreDouble::infinity())
                                ->setPadding(EdgeInsetsPadding::fromSymmetric(vertical: 10, horizontal: 15))
                                ->setMargin(EdgeInsetsPadding::fromSymmetric(horizontal: 15))
                                ->setDecoration((new BoxDecoration)
                                    ->setBorderRadius(BorderRadius::all(5))
                                    ->setBorder(Border::all(Color::fromRGB(210, 210, 210), 1))
                                    ->setColor(Color::fromRGB(255, 255, 255))
                                )
                                ->setChild((new Column)
                                    ->setChildren([
                                        (new Container)
                                            ->setWidth(CoreDouble::infinity())
                                            ->setChild((new Column)
                                                ->setChildren([
                                                    (new Row)
                                                        ->setMainAxisAlignment(MainAxisAlignment::spaceBetween())
                                                        ->setCrossAxisAlignment(CrossAxisAlignment::center())
                                                        ->setChildren([
                                                            (new Text('Sipariş Notu'))
                                                                ->setStyle((new TextStyle)
                                                                    ->setFontSize(17)
                                                                    ->setFontWeight(FontWeight::w600())),
                                                            (new Text('')),
                                                        ]),
                                                    (new SizedBox(height: new CoreDouble(10))),
                                                    (new TextFormField('note', 'paymentForm'))
                                                        ->setMinLines(2)
                                                        ->setDecoration((new InputDecoration)
                                                            ->setEnabledBorder(
                                                                (new OutlineInputBorder)
                                                                    ->setBorderRadius(BorderRadius::all(10))
                                                                    ->setBorderSide((new BorderSide(Color::fromRGB(180, 180, 180)))))
                                                            ->setBorder(
                                                                (new OutlineInputBorder)
                                                                    ->setBorderRadius(BorderRadius::all(10))
                                                                    ->setBorderSide((new BorderSide(Color::fromRGB(180, 180, 180)))))
                                                            ->setFilled(true)
                                                            ->setFillColor(Color::fromRGB(255, 255, 255))
                                                            ->setHintText('Siparişiniz ile ilgili notu bu alana yazabilirsiniz.')
                                                            ->setHintMaxLines(2)
                                                            ->setHintStyle((new TextStyle)
                                                                ->setColor(Color::fromRGB(180, 180, 180))
                                                                ->setFontSize(14))
                                                        )
                                                        ->setStyle((new TextStyle)
                                                            ->setColor(Color::fromRGB(180, 180, 180))
                                                            ->setFontSize(14)),
                                                    (new SizedBox(height: new CoreDouble(10))),
                                                ])),
                                    ])
                                )]))))
            ->setBottomNavigationBar($this->getBottomNavigationBar());
    }

    public function getBottomNavigationBar(): Json
    {
        return (new BottomAppBar)
            ->setChild((new IntrinsicHeight)
                ->setChild((new Container)
                    ->setPadding(EdgeInsetsPadding::fromSymmetric(vertical: 1, horizontal: 1))
                    ->setColor(Color::fromRGB(255, 255, 255))
                    ->setChild((new Row)
                        ->setCrossAxisAlignment(CrossAxisAlignment::center())
                        ->setMainAxisAlignment(MainAxisAlignment::spaceBetween())
                        ->setChildren([
                            (new Column)
                                ->setCrossAxisAlignment(CrossAxisAlignment::start())
                                ->setChildren([
                                    (new Text('Toplam Tutar'))
                                        ->setStyle((new TextStyle)
                                            ->setColor(Color::fromRGB(0, 0, 0))
                                            ->setFontSize(16)
                                            ->setFontWeight(FontWeight::w600())),
                                    (new SizedBox(width: new CoreDouble(5))),
                                    (new Text($this->getTotalPrice()))
                                        ->setStyle((new TextStyle)
                                            ->setColor(Color::fromRGB(15, 146, 70))
                                            ->setFontSize(18)
                                            ->setFontWeight(FontWeight::w600())),
                                ]),
                            (new GestureDetector)
                                ->setOnTap(
                                    PaymentPage::instance()::getStateEvent(StartPaymentState::getName())
                                )->setChild((new Container)
                                ->setPadding(EdgeInsetsPadding::fromSymmetric(10, 20))
                                ->setDecoration((new BoxDecoration)
                                    ->setColor(Color::fromRGB(15, 146, 70))
                                    ->setBorderRadius(BorderRadius::all(10)))
                                ->setChild((new Text('Ödemeyi Tamamla'))->setStyle((new TextStyle)
                                    ->setColor(Color::fromRGB(255, 255, 255))
                                    ->setFontWeight(FontWeight::w600())))),
                        ]))));
    }

    public function getStoredCardsWidget(): Json
    {
        $widget = (new RadioGroupFormField)
            ->setValidator((new RequiredValidator)
                ->setErrorMessage('Lütfen bir kart seçin'))
            ->setName('selected_card');

        $options = [];

        foreach ($this->getStoredCards() as $storedCard) {
            $options[] = (new RadioListTile)
                ->setValue(new DynamicValue($storedCard->id))
                ->setContentPadding(EdgeInsetsPadding::fromSymmetric(horizontal: 0))
                ->setTitle(new Text(sprintf('%s Kartım', $storedCard->alias)));
        }

        $widget->setOptions($options);

        return $widget;
    }

    public function getTotalPrice(): string
    {
        return '0.00 TL';
    }

    public function getAddressWidget(): Json
    {
        return (new Container)
            ->setWidth(CoreDouble::infinity())
            ->setPadding(EdgeInsetsPadding::fromSymmetric(vertical: 10, horizontal: 15))
            ->setMargin(EdgeInsetsPadding::fromSymmetric(horizontal: 15))
            ->setDecoration((new BoxDecoration)
                ->setBorderRadius(BorderRadius::all(5))
                ->setBorder(Border::all(Color::fromRGB(210, 210, 210), 1))
                ->setColor(Color::fromRGB(255, 255, 255))
            )
            ->setChild((new Column)
                ->setChildren([
                    (new Text('Teslimat Adresin'))
                        ->setStyle((new TextStyle)
                            ->setFontSize(17)
                            ->setFontWeight(FontWeight::w600())),
                    (new SizedBox(height: new CoreDouble(10))),
                    (new Text($this->getAddress()))
                        ->setStyle((new TextStyle)
                            ->setColor(Color::fromRGB(180, 180, 180))
                            ->setFontSize(14)),
                ]));
    }

    public function getAddress(): string
    {
        return $this->user->currentAddress->displayAddress;
    }

    public function getBottomInformationWidget(): Json
    {
        return (new Text('Kartınızı Iyzico güvencesinde kaydetmek için kartınıza bir takma isim girin.'))
            ->setOverflow(TextOverflow::ellipsis())
            ->setMaxLines(5)
            ->setSoftWrap(true)
            ->setStyle((new TextStyle)
                ->setColor(Color::fromRGB(120, 120, 120))
                ->setFontSize(14));
    }
}
