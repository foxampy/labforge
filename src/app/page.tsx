import Hero from "@/components/Hero";
import Reveal from "@/components/Reveal";
import LeadForm from "@/components/LeadForm";
import { ServiceCard, BrandCard, SectionHeading } from "@/components/ui";
import { JsonLd } from "@/components/JsonLd";
import { services } from "@/data/services";
import { brands } from "@/data/brands";
import { company } from "@/data/company";
import { faqJsonLd } from "@/lib/seo";
import Link from "next/link";

const homeFaq = [
  { q: "Сколько стоит ремонт?", a: "Стоимость зависит от модели авто и характера неисправности. Мы делаем точный расчёт после диагностики и согласуем смету до начала работ — без навязанных услуг." },
  { q: "Даёте ли гарантию?", a: "Да, на все работы и установленные запчасти предоставляется письменная гарантия — в зависимости от вида работ до 2 лет." },
  { q: "С какими марками вы работаете?", a: "Обслуживаем большинство европейских, азиатских и китайских марок: Volkswagen, Audi, BMW, Mercedes-Benz, Toyota, Kia, Hyundai, Geely и другие." },
  { q: "Нужно ли записываться заранее?", a: "Желательно — так мы подготовим запчасти и примем вас без ожидания. Записаться можно по телефону, через форму на сайте или в Telegram." },
];

const steps = [
  { n: "01", t: "Заявка или звонок", d: "Оставляете заявку — перезваниваем за 15 минут и уточняем детали." },
  { n: "02", t: "Диагностика", d: "Находим причину неисправности и составляем точную смету." },
  { n: "03", t: "Согласование", d: "Утверждаем стоимость и сроки. Без сюрпризов в финале." },
  { n: "04", t: "Ремонт и гарантия", d: "Выполняем работы и выдаём письменную гарантию." },
];

export default function HomePage() {
  return (
    <>
      <Hero />

      {/* Услуги */}
      <section id="uslugi" className="container-x mt-10">
        <Reveal>
          <SectionHeading
            eyebrow="Направления"
            title="Услуги автосервиса в Минске"
            subtitle="Полный спектр работ по ремонту и обслуживанию — от диагностики до кузовного ремонта."
          />
        </Reveal>
        <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {services.map((s, i) => (
            <Reveal key={s.slug} delay={(i % 3) * 80}>
              <ServiceCard slug={s.slug} title={s.title} icon={s.icon} excerpt={s.excerpt} />
            </Reveal>
          ))}
        </div>
        <div className="mt-8 text-center">
          <Link href="/uslugi" className="btn-ghost">Смотреть все услуги и прайс</Link>
        </div>
      </section>

      {/* Почему мы */}
      <section className="container-x mt-24">
        <Reveal>
          <SectionHeading eyebrow="Почему Livada Center" title="Ремонт без переплат и сюрпризов" center />
        </Reveal>
        <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {company.usp.map((u, i) => (
            <Reveal key={u.title} delay={(i % 4) * 80}>
              <div className="card-3d h-full p-6">
                <h3 className="text-lg font-bold text-white">{u.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-slate-400">{u.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </section>

      {/* Процесс */}
      <section className="container-x mt-24">
        <Reveal>
          <SectionHeading eyebrow="Как мы работаем" title="Четыре простых шага" />
        </Reveal>
        <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {steps.map((s, i) => (
            <Reveal key={s.n} delay={(i % 4) * 80}>
              <div className="card-3d h-full p-6">
                <span className="text-3xl font-extrabold text-brand-400/40">{s.n}</span>
                <h3 className="mt-2 text-lg font-bold text-white">{s.t}</h3>
                <p className="mt-2 text-sm leading-relaxed text-slate-400">{s.d}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </section>

      {/* Марки */}
      <section id="marki" className="container-x mt-24">
        <Reveal>
          <SectionHeading
            eyebrow="Марки авто"
            title="Обслуживаем большинство марок"
            subtitle="Знаем особенности каждой марки и используем дилерское диагностическое оборудование."
          />
        </Reveal>
        <div className="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7">
          {brands.map((b, i) => (
            <Reveal key={b.slug} delay={(i % 7) * 50}>
              <BrandCard slug={b.slug} name={b.name} country={b.country} />
            </Reveal>
          ))}
        </div>
      </section>

      {/* Заявка */}
      <section id="zayavka" className="container-x mt-24 scroll-mt-24">
        <div className="grid items-center gap-10 lg:grid-cols-2">
          <Reveal>
            <SectionHeading
              eyebrow="Запись онлайн"
              title="Оставьте заявку — перезвоним за 15 минут"
              subtitle="Опишите проблему, и мы рассчитаем стоимость ремонта и предложим удобное время."
            />
            <ul className="mt-6 space-y-3">
              {["Бесплатная консультация по телефону", "Честный расчёт до начала работ", "Гарантия на все виды работ"].map((t) => (
                <li key={t} className="flex items-center gap-3 text-slate-300">
                  <span className="grid h-6 w-6 place-items-center rounded-full bg-brand-500/20 text-sm text-brand-300">✓</span>
                  {t}
                </li>
              ))}
            </ul>
            <div className="mt-6 flex flex-wrap gap-3">
              <a href={company.phoneHref} className="btn-primary">📞 {company.phone}</a>
              <a href={company.telegram} target="_blank" rel="noopener" className="btn-ghost">✈ Telegram</a>
            </div>
          </Reveal>
          <Reveal delay={120}>
            <LeadForm />
          </Reveal>
        </div>
      </section>

      {/* FAQ */}
      <section className="container-x mt-24">
        <Reveal>
          <SectionHeading eyebrow="Вопросы и ответы" title="Частые вопросы" center />
        </Reveal>
        <div className="mx-auto mt-10 max-w-3xl space-y-3">
          {homeFaq.map((f, i) => (
            <Reveal key={i} delay={(i % 4) * 60}>
              <details className="card-3d group p-5">
                <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-white">
                  {f.q}
                  <span className="text-brand-400 transition-transform group-open:rotate-45">+</span>
                </summary>
                <p className="mt-3 text-sm leading-relaxed text-slate-400">{f.a}</p>
              </details>
            </Reveal>
          ))}
        </div>
      </section>

      <JsonLd data={faqJsonLd(homeFaq)} />
    </>
  );
}
