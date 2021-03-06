<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Link;
use App\Models\Menu;
use App\Models\Offer;
use App\Models\Page;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\SocialSetting;
use App\Models\Video;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use App\Models\Leader;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Xavfsiz daryo unitar korxonasi');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Xavfsiz daryo unitar korxonasi');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Xavfsiz daryo unitar korxonasi');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $menus = Menu::with('page')->where('is_active', 1)->orderBy('order')->get();
//        dd($menus);
        $sliders = Slider::where('is_active', 1)->get();
        $lastNews = Blog::orderByDesc('created_at')->limit(4)->get();
        $allNews = Blog::inRandomOrder()->limit(6)->get();
        $video = Video::first();
        $videos = Video::where('is_active', 1)->orderBy('updated_at')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::first();
        return view('frontend.index', compact('sliders', 'lastNews', 'allNews', 'videos', 'video', 'links', 'setting', 'menus'));
    }

    public function news()
    {
        SEOMeta::setTitle('Yangiliklar');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Yangiliklar');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Yangiliklar');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $news = Blog::with('category')->where('is_active', 1)->orderByDesc('created_at')->paginate(8);
        return view('frontend.news', compact('links', 'setting', 'news', 'menus'));
    }

    public function new($id)
    {
        $new = Blog::with('category')->where('is_active', 1)->findOrFail($id);
        SEOMeta::setTitle($new->getValue('title'));
        SEOMeta::setDescription(strip_tags((Str::limit($new->getValue('content'), 150))));
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription(strip_tags(Str::limit($new->getValue('content'), 150)));
        OpenGraph::setTitle($new->getValue('title'));
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset($new->image));

        JsonLd::setTitle($new->getValue('title'));
        JsonLd::setDescription(strip_tags(Str::limit($new->getValue('content'), 150)));

        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $allNews = Blog::where('id', '!=', $id)->inRandomOrder()->limit(8)->get();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.new', compact('links', 'menus', 'setting', 'new', 'socials', 'allNews'));
    }

    public function sections()
    {
        SEOMeta::setTitle('Hududiy bo??linmalar');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Hududiy bo??linmalar');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Hududiy bo??linmalar');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $sections = Section::orderByDesc('created_at')->get();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.sections', compact('links', 'setting', 'socials', 'sections', 'menus', 'randomPages'));
    }

    public function section($id)
    {
        $section = Section::findOrFail($id);
        SEOMeta::setTitle($section->getValue('name'));
        SEOMeta::setDescription(strip_tags($section->getValue('bio')));
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription(strip_tags($section->getValue('bio')));
        OpenGraph::setTitle($section->getValue('name'));
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset($section->image));

        JsonLd::setTitle($section->getValue('name'));
        JsonLd::setDescription(strip_tags($section->getValue('bio')));

        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.section', compact('links', 'setting', 'socials', 'section', 'menus', 'randomPages'));
    }

    public function leaders()
    {
        SEOMeta::setTitle('Rahbariyat');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Rahbariyat');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Rahbariyat');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $leaders = Leader::orderByDesc('created_at')->get();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.leaders', compact('links', 'setting', 'socials', 'leaders', 'menus', 'randomPages'));
    }

    public function leader($id)
    {
        $leader = Leader::findOrFail($id);
        SEOMeta::setTitle($leader->getValue('name'));
        SEOMeta::setDescription(strip_tags($leader->getValue('bio')));
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription(strip_tags($leader->getValue('bio')));
        OpenGraph::setTitle($leader->getValue('name'));
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset($leader->image));

        JsonLd::setTitle($leader->getValue('name'));
        JsonLd::setDescription(strip_tags($leader->getValue('bio')));

        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.leader', compact('links', 'setting', 'socials', 'leader', 'menus', 'randomPages'));
    }

    public function page($slug)
    {
        SEOMeta::setTitle('Xavfsiz daryo unitar korxonasi');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Xavfsiz daryo unitar korxonasi');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Xavfsiz daryo unitar korxonasi');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $page = Page::where('slug', $slug)->firstOrFail();
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.pages', compact('links', 'setting', 'socials', 'menus', 'page', 'randomPages'));
    }

    public function waitList()
    {
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
        return view('frontend.user.waitlist', compact('links', 'setting', 'socials', 'menus'));
    }

    public function offers()
    {
        SEOMeta::setTitle('Loyihalar');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Loyihalar');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Loyihalar');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
        $offers = Offer::where('is_active', 1)->orderByDesc('created_at')->get();
        return view('frontend.offers', compact('links', 'setting', 'socials', 'menus', 'offers'));
    }

    public function contact()
    {
        SEOMeta::setTitle('Bog`lanish');
        SEOMeta::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        SEOMeta::setCanonical('https://xdduk.uz');

        OpenGraph::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');
        OpenGraph::setTitle('Bog`lanish');
        OpenGraph::setUrl('https://xdduk.uz');
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage(asset('frontend/img/logo.png'));

        JsonLd::setTitle('Bog`lanish');
        JsonLd::setDescription('Xavfsiz daryo unitar korxonasining rasmiy sayti');

        $menus = Menu::where('is_active', 1)->orderBy('order')->get();
        $randomPages = Page::with('menu')->where('is_active', 1)->inRandomOrder()->limit(5)->get();
        $links = Link::where('is_active', 1)->get();
        $setting = Setting::firstOrFail();
        $socials = SocialSetting::where('is_active', 1)->get();
//        var_dump($randomPages);
        return view('frontend.contact', compact('links', 'setting', 'socials', 'menus', 'randomPages'));
    }

    public function text(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required',
            'text' => 'required',
            'captcha' => 'required|captcha'
        ],
            [
                'captcha.captcha' => 'Captcha not valid',
                'captcha.required' => 'Captcha is required'
            ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->sname = $request->sname;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->text = $request->text;
        $contact->save();

        return redirect()->back()->with('message', 'Sizning xabar muvaffaqiyatli jo`natildi');
    }
}
