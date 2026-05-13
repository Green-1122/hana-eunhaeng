import { ShieldCheck } from "lucide-react";

const columns = [
  {
    title: "Banking",
    links: ["Checking", "Savings", "Cards", "International transfers", "Crypto"],
  },
  {
    title: "Invest",
    links: ["Portfolios", "Stocks", "Watchlists", "Loans", "Calculators"],
  },
  {
    title: "Company",
    links: ["About", "Careers", "Press", "Security", "Contact"],
  },
  {
    title: "Legal",
    links: ["Terms", "Privacy", "Cookies", "Disclosures", "Accessibility"],
  },
];

export function SiteFooter() {
  return (
    <footer className="border-t border-border bg-primary text-primary-foreground">
      <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid gap-12 lg:grid-cols-5">
          <div className="lg:col-span-2">
            <div className="flex items-center gap-2">
              <span className="grid h-9 w-9 place-items-center rounded-md bg-gold text-gold-foreground">
                <ShieldCheck className="h-5 w-5" />
              </span>
              <span className="font-display text-xl font-semibold">Hana·Eunhaeng</span>
            </div>
            <p className="mt-4 max-w-sm text-sm text-primary-foreground/70">
              Modern banking built on institutional-grade security. Member protections
              up to $250,000. Equal Housing Lender.
            </p>
            <div className="mt-6 flex gap-2 text-xs text-primary-foreground/60">
              <span className="rounded border border-primary-foreground/20 px-2 py-1">FDIC</span>
              <span className="rounded border border-primary-foreground/20 px-2 py-1">SIPC</span>
              <span className="rounded border border-primary-foreground/20 px-2 py-1">SOC 2</span>
              <span className="rounded border border-primary-foreground/20 px-2 py-1">PCI DSS</span>
            </div>
          </div>

          {columns.map((col) => (
            <div key={col.title}>
              <h4 className="text-sm font-semibold uppercase tracking-wider text-gold">
                {col.title}
              </h4>
              <ul className="mt-4 space-y-3 text-sm text-primary-foreground/75">
                {col.links.map((link) => (
                  <li key={link}>
                    <a href="#" className="hover:text-primary-foreground">{link}</a>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="mt-12 flex flex-col items-start justify-between gap-4 border-t border-primary-foreground/10 pt-8 text-xs text-primary-foreground/60 sm:flex-row sm:items-center">
          <p>© {new Date().getFullYear()} Hana-Eunhaeng Financial Group. All rights reserved.</p>
          <p>Routing #: 021000021 · NMLS ID 000000</p>
        </div>
      </div>
    </footer>
  );
}
