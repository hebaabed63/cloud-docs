# 📄 CloudDocs - نظام إدارة المستندات السحابي

نظام لإدارة وتحميل المستندات سهل الاستخدام، يسمح للمستخدمين برفع المستندات (PDF / Word)، وتصنيفها، واستعراضها، وترتيبها، والبحث ضمنها.

## 🚀 المميزات

- 🗂️ رفع مستندات بصيغ PDF وDOCX
- 🧠 استخراج عنوان المستند تلقائيًا من أول سطر
- 📦 تخزين الملفات داخل `storage/app/documents`
- 🔗 إنشاء رابط رمزي للملفات عبر `public/storage`
- 👀 استعراض مباشر للمستند داخل المتصفح (PDF/Word)
- 🔍 بحث داخل قاعدة البيانات
- 📊 ترتيب المستندات تصاعديًا / تنازليًا
- 📁 واجهة تحميل بالسحب والإفلات Drag & Drop
- 🧠 حساب الزمن المستغرق للبحث والفرز

## 🛠️ التقنيات المستخدمة

- Laravel 10
- CSS
- MySQL
- JavaScript
- Blade
- Git / GitHub

🗂️ المسارات المهمة

/documents/upload: صفحة رفع مستند جديد

/documents: عرض الواجهة الترحيبية 

/documents/search: بحث وترتيب المستندات

## 👩‍💻 المطورون

- هبة إبراهيم عابد
- رؤى مشتهى

## ⚙️ تشغيل المشروع

```bash
git clone https://github.com/hebaabed63/cloud-docs.git
cd cloud-docs

composer install

cp .env.example .env
php artisan key:generate
php artisan migrate

php artisan storage:link
php artisan serve
