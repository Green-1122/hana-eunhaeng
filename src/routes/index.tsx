import { createFileRoute, Link } from "@tanstack/react-router";
import {
  ArrowRight,
  CreditCard,
  Globe2,
  LineChart,
  Lock,
  PiggyBank,
  ShieldCheck,
  Smartphone,
  Sparkles,
  Wallet,
} from "lucide-react";
import { SiteHeader } from "@/components/site-header";
import { SiteFooter } from "@/components/site-footer";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "Hana-Eunhaeng — Modern banking, institutional trust" },
      {
        name: "description",
        content:
          "Open a Hana-Eunhaeng account in minutes. Checking, savings, cards, international transfers, investments and crypto — protected by bank-grade security.",
      },
      { property: "og:title", content: "Hana-Eunhaeng — Modern banking, institutional trust" },
      {
        property: "og:description",
        content:
          "Premium digital banking for individuals and businesses. FDIC-insured, 256-bit encryption, 24/7 fraud monitoring.",
      },
    ],
  }),
  component: HomePage,
});

const features = [
  {
    icon: Wallet,
    title: "Everyday banking",
    body: "Checking and high-yield savings with no hidden fees, instant peer transfers and global ATM access.",
  },
  {
    icon: CreditCard,
    title: "Smart cards",
    body: "Issue virtual or physical Visa, Mastercard and Amex cards. Freeze, set limits and rotate PINs in seconds.",
  },
  {
    icon: Globe2,
    title: "Worldwide transfers",
    body: "Send funds in 40+ currencies with SWIFT, IMF, COT and Tax Code support and live exchange rates.",
  },
  {
    icon: LineChart,
    title: "Invest with confidence",
    body: "Build a portfolio of stocks and managed funds with transparent ROI and risk indicators.",
  },
  {
    icon: PiggyBank,
    title: "Goal-based savings",
    body: "Automate contributions, project growth, and visualize progress toward each life milestone.",
  },
  {
    icon: Sparkles,
    title: "Crypto, simplified",
    body: "Hold and transfer BTC, ETH, USDT and BNB alongside your fiat balance, with built-in safeguards.",
  },
];

const stats = [
  { value: "$48B+", label: "Assets under custody" },
  { value: "1.2M", label: "Customers worldwide" },
  { value: "99.99%", label: "Platform uptime" },
  { value: "A+", label: "S&P credit rating" },
];

const testimonials = [
  {
    quote:
      "We replaced three legacy bank relationships with Hana-Eunhaeng. Treasury reporting that took a week now happens in real time.",
    name: "Mei Tanaka",
    role: "CFO, Lumen Robotics",
  },
  {
    quote:
      "The international transfers are the most transparent I've ever seen. Every fee, every rate, before I confirm.",
    name: "David Okafor",
    role: "Independent consultant",
  },
  {
    quote:
      "Their security team caught a card-testing attack within minutes. That alone is worth the move.",
    name: "Priya Raman",
    role: "Founder, Northwind Studios",
  },
];

const faqs = [
  {
    q: "Is Hana-Eunhaeng a real bank?",
    a: "Yes. Deposits are held at our member institutions and insured by the FDIC up to $250,000 per depositor. Investment accounts are SIPC-protected up to $500,000.",
  },
  {
    q: "How long does it take to open an account?",
    a: "Most personal accounts are approved in under 5 minutes. Business and wealth accounts typically take 1–2 business days after document review.",
  },
  {
    q: "What does it cost?",
    a: "Personal checking and savings have no monthly fee and no minimum balance. Wire transfers, premium cards and wealth management have transparent published rates.",
  },
  {
    q: "How is my money protected?",
    a: "We use 256-bit encryption in transit and at rest, hardware-backed authentication, continuous fraud monitoring and SOC 2 Type II controls audited annually.",
  },
];

function HomePage() {
  return (
    <div className="flex min-h-screen flex-col bg-background">
      <SiteHeader />

      {/* Hero */}
      <section className="relative overflow-hidden border-b border-border">
        <div
          aria-hidden
          className="absolute inset-0 -z-10"
          style={{
            backgroundImage:
              "radial-gradient(at 70% 20%, color-mix(in oklab, var(--gold) 14%, transparent) 0px, transparent 50%), radial-gradient(at 10% 90%, color-mix(in oklab, var(--primary) 8%, transparent) 0px, transparent 60%)",
          }}
        />
        <div className="mx-auto grid max-w-7xl gap-16 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-28">
          <div>
            <div className="inline-flex items-center gap-2 rounded-full border border-border bg-card px-3 py-1 text-xs font-medium text-muted-foreground">
              <span className="h-1.5 w-1.5 rounded-full bg-success" />
              FDIC insured · Member protections to $250,000
            </div>
            <h1 className="mt-6 font-display text-5xl font-semibold leading-tight tracking-tight text-foreground sm:text-6xl">
              Banking with the trust of an institution,
              <span className="text-gold"> the speed of software.</span>
            </h1>
            <p className="mt-6 max-w-lg text-lg text-muted-foreground">
              Open a Hana-Eunhaeng account to manage everyday banking, international
              transfers, investments, and crypto from one secure dashboard.
            </p>
            <div className="mt-8 flex flex-wrap gap-3">
              <Button asChild size="lg">
                <Link to="/signup">
                  Open an account
                  <ArrowRight className="ml-2 h-4 w-4" />
                </Link>
              </Button>
              <Button asChild size="lg" variant="outline">
                <Link to="/login">Sign in</Link>
              </Button>
            </div>
            <p className="mt-4 text-xs text-muted-foreground">
              No credit check · No monthly fee · 5-minute approval
            </p>
          </div>

          {/* Hero card mock */}
          <div className="relative">
            <Card className="relative overflow-hidden rounded-3xl border-border/60 bg-card p-6 shadow-[var(--shadow-elegant)]">
              <div className="flex items-start justify-between">
                <div>
                  <p className="text-xs uppercase tracking-wider text-muted-foreground">
                    Total balance
                  </p>
                  <p className="mt-2 font-display text-4xl font-semibold tracking-tight">
                    $128,540.22
                  </p>
                  <p className="mt-1 text-xs text-success">
                    +$2,184.10 this month
                  </p>
                </div>
                <span className="rounded-md bg-primary px-2 py-1 text-xs text-primary-foreground">
                  Premier
                </span>
              </div>

              <div className="mt-6 grid grid-cols-2 gap-3">
                {[
                  { label: "Checking", value: "$24,180" },
                  { label: "Savings", value: "$58,902" },
                  { label: "Investments", value: "$41,210" },
                  { label: "Crypto", value: "$4,247" },
                ].map((a) => (
                  <div
                    key={a.label}
                    className="rounded-xl border border-border bg-surface p-3"
                  >
                    <p className="text-xs text-muted-foreground">{a.label}</p>
                    <p className="mt-1 text-base font-semibold">{a.value}</p>
                  </div>
                ))}
              </div>

              <div className="mt-6 rounded-2xl bg-primary p-5 text-primary-foreground">
                <div className="flex items-center justify-between text-xs uppercase tracking-wider text-primary-foreground/60">
                  <span>Hana·Eunhaeng</span>
                  <span>Premier · Visa</span>
                </div>
                <p className="mt-8 font-mono text-lg tracking-widest">
                  4421 ·· ·· 8830
                </p>
                <div className="mt-4 flex items-end justify-between text-xs text-primary-foreground/70">
                  <span>SARAH J. KIM</span>
                  <span>10/29</span>
                </div>
              </div>
            </Card>
            <div
              aria-hidden
              className="pointer-events-none absolute -bottom-8 -right-8 h-40 w-40 rounded-full bg-gold/30 blur-3xl"
            />
          </div>
        </div>
      </section>

      {/* Stats */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
          {stats.map((s) => (
            <div key={s.label}>
              <p className="font-display text-3xl font-semibold text-primary">{s.value}</p>
              <p className="mt-1 text-sm text-muted-foreground">{s.label}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Features */}
      <section id="features" className="border-b border-border">
        <div className="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
          <div className="max-w-2xl">
            <p className="text-sm font-semibold uppercase tracking-wider text-gold">
              One platform
            </p>
            <h2 className="mt-3 font-display text-4xl font-semibold tracking-tight">
              Everything you'd expect from your bank, and nothing you wouldn't.
            </h2>
            <p className="mt-4 text-muted-foreground">
              Hana-Eunhaeng combines accounts, payments, investing and crypto into a
              single, audited platform — so you spend less time switching tools.
            </p>
          </div>

          <div className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {features.map((f) => (
              <Card key={f.title} className="border-border/60 p-6 transition-shadow hover:shadow-[var(--shadow-card)]">
                <span className="grid h-11 w-11 place-items-center rounded-lg bg-primary/10 text-primary">
                  <f.icon className="h-5 w-5" />
                </span>
                <h3 className="mt-5 text-lg font-semibold">{f.title}</h3>
                <p className="mt-2 text-sm text-muted-foreground">{f.body}</p>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Trust / security */}
      <section className="border-b border-border bg-primary text-primary-foreground">
        <div className="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
          <div>
            <p className="text-sm font-semibold uppercase tracking-wider text-gold">
              Security first
            </p>
            <h2 className="mt-3 font-display text-4xl font-semibold tracking-tight">
              Built like a bank. Audited like one, too.
            </h2>
            <p className="mt-4 max-w-lg text-primary-foreground/80">
              Multi-factor authentication, hardware-bound device keys, encrypted
              tokenized cards, and continuous behavioral fraud detection — backed by
              SOC 2 Type II and PCI DSS Level 1 controls.
            </p>
          </div>
          <div className="grid gap-4 sm:grid-cols-2">
            {[
              { icon: ShieldCheck, title: "FDIC insured", body: "Deposits protected up to $250,000 per depositor." },
              { icon: Lock, title: "256-bit encryption", body: "AES-256 in transit and at rest, end-to-end." },
              { icon: Smartphone, title: "Device 2FA", body: "Hardware-bound login on every trusted device." },
              { icon: Sparkles, title: "Real-time fraud AI", body: "Anomalies flagged within 200ms of activity." },
            ].map((b) => (
              <div key={b.title} className="rounded-2xl border border-primary-foreground/10 bg-primary-foreground/5 p-5">
                <span className="grid h-9 w-9 place-items-center rounded-md bg-gold text-gold-foreground">
                  <b.icon className="h-4 w-4" />
                </span>
                <h3 className="mt-4 text-base font-semibold">{b.title}</h3>
                <p className="mt-1 text-sm text-primary-foreground/70">{b.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="border-b border-border">
        <div className="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
          <h2 className="font-display text-4xl font-semibold tracking-tight">
            Trusted by founders, families, and CFOs.
          </h2>
          <div className="mt-10 grid gap-6 lg:grid-cols-3">
            {testimonials.map((t) => (
              <Card key={t.name} className="border-border/60 p-6">
                <p className="text-base text-foreground">"{t.quote}"</p>
                <div className="mt-6 border-t border-border pt-4">
                  <p className="font-semibold">{t.name}</p>
                  <p className="text-sm text-muted-foreground">{t.role}</p>
                </div>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-3 lg:px-8">
          <div>
            <p className="text-sm font-semibold uppercase tracking-wider text-gold">
              Questions
            </p>
            <h2 className="mt-3 font-display text-4xl font-semibold tracking-tight">
              Answers, in plain language.
            </h2>
            <p className="mt-4 text-muted-foreground">
              We believe a bank should explain how your money works. Here are the
              essentials.
            </p>
          </div>
          <div className="lg:col-span-2">
            <Accordion type="single" collapsible className="w-full">
              {faqs.map((f, i) => (
                <AccordionItem key={f.q} value={`item-${i}`}>
                  <AccordionTrigger className="text-left text-base font-semibold">
                    {f.q}
                  </AccordionTrigger>
                  <AccordionContent className="text-muted-foreground">
                    {f.a}
                  </AccordionContent>
                </AccordionItem>
              ))}
            </Accordion>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section>
        <div className="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
          <Card className="overflow-hidden border-none bg-primary p-10 text-primary-foreground sm:p-14">
            <div className="grid gap-8 lg:grid-cols-2 lg:items-center">
              <div>
                <h2 className="font-display text-4xl font-semibold tracking-tight">
                  Open your account in minutes.
                </h2>
                <p className="mt-3 max-w-md text-primary-foreground/80">
                  Free, paperless, and protected from day one.
                </p>
              </div>
              <div className="flex flex-wrap gap-3 lg:justify-end">
                <Button asChild size="lg" className="bg-gold text-gold-foreground hover:bg-gold/90">
                  <Link to="/signup">Get started</Link>
                </Button>
                <Button
                  asChild
                  size="lg"
                  variant="outline"
                  className="border-primary-foreground/30 bg-transparent text-primary-foreground hover:bg-primary-foreground/10"
                >
                  <Link to="/login">I already bank here</Link>
                </Button>
              </div>
            </div>
          </Card>
        </div>
      </section>

      <SiteFooter />
    </div>
  );
}
