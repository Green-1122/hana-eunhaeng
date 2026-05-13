import { Link } from "@tanstack/react-router";
import { ShieldCheck } from "lucide-react";
import type { ReactNode } from "react";

export function AuthShell({
  title,
  subtitle,
  children,
  footer,
}: {
  title: string;
  subtitle?: string;
  children: ReactNode;
  footer?: ReactNode;
}) {
  return (
    <div className="grid min-h-screen lg:grid-cols-2">
      {/* Brand panel */}
      <div className="relative hidden overflow-hidden bg-primary text-primary-foreground lg:flex lg:flex-col lg:justify-between lg:p-12">
        <Link to="/" className="flex items-center gap-2">
          <span className="grid h-10 w-10 place-items-center rounded-md bg-gold text-gold-foreground">
            <ShieldCheck className="h-5 w-5" />
          </span>
          <span className="font-display text-2xl font-semibold">Hana·Eunhaeng</span>
        </Link>

        <div className="relative z-10 max-w-md">
          <p className="font-display text-3xl leading-tight">
            "Banking built on trust, engineered for the next century."
          </p>
          <p className="mt-6 text-sm text-primary-foreground/70">
            FDIC insured · 256-bit encryption · Continuous fraud monitoring
          </p>
        </div>

        <div
          aria-hidden
          className="absolute inset-0 opacity-30"
          style={{
            backgroundImage:
              "radial-gradient(at 80% 0%, color-mix(in oklab, var(--gold) 40%, transparent) 0px, transparent 50%), radial-gradient(at 0% 100%, color-mix(in oklab, var(--gold) 20%, transparent) 0px, transparent 50%)",
          }}
        />
      </div>

      {/* Form panel */}
      <div className="flex flex-col bg-background">
        <div className="flex items-center justify-between border-b border-border px-6 py-4 lg:hidden">
          <Link to="/" className="flex items-center gap-2">
            <span className="grid h-8 w-8 place-items-center rounded-md bg-primary text-primary-foreground">
              <ShieldCheck className="h-4 w-4" />
            </span>
            <span className="font-display text-lg font-semibold">Hana·Eunhaeng</span>
          </Link>
        </div>

        <div className="flex flex-1 items-center justify-center px-6 py-12 sm:px-12">
          <div className="w-full max-w-md">
            <h1 className="font-display text-3xl font-semibold tracking-tight text-foreground">
              {title}
            </h1>
            {subtitle ? (
              <p className="mt-2 text-sm text-muted-foreground">{subtitle}</p>
            ) : null}

            <div className="mt-8">{children}</div>

            {footer ? (
              <div className="mt-8 text-center text-sm text-muted-foreground">{footer}</div>
            ) : null}
          </div>
        </div>
      </div>
    </div>
  );
}
