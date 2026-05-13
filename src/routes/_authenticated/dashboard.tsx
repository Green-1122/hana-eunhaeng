import { createFileRoute, useNavigate } from "@tanstack/react-router";
import { useEffect, useState } from "react";
import { Loader2, LogOut, ShieldCheck } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { supabase } from "@/integrations/supabase/client";

export const Route = createFileRoute("/_authenticated/dashboard")({
  head: () => ({ meta: [{ title: "Dashboard · Hana-Eunhaeng" }] }),
  component: DashboardPage,
});

interface ProfileRow {
  full_name: string | null;
  customer_number: string | null;
  kyc_status: string;
}

function DashboardPage() {
  const navigate = useNavigate();
  const [profile, setProfile] = useState<ProfileRow | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      const { data: userData } = await supabase.auth.getUser();
      if (!userData.user) return;
      const { data } = await supabase
        .from("profiles")
        .select("full_name, customer_number, kyc_status")
        .eq("id", userData.user.id)
        .maybeSingle();
      setProfile(data);
      setLoading(false);
    })();
  }, []);

  const handleSignOut = async () => {
    await supabase.auth.signOut();
    navigate({ to: "/" });
  };

  return (
    <div className="min-h-screen bg-surface">
      <header className="border-b border-border bg-background">
        <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-2">
            <span className="grid h-9 w-9 place-items-center rounded-md bg-primary text-primary-foreground">
              <ShieldCheck className="h-5 w-5" />
            </span>
            <span className="font-display text-lg font-semibold">Hana·Eunhaeng</span>
          </div>
          <Button variant="ghost" size="sm" onClick={handleSignOut}>
            <LogOut className="mr-2 h-4 w-4" />
            Sign out
          </Button>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {loading ? (
          <div className="flex items-center gap-2 text-muted-foreground">
            <Loader2 className="h-4 w-4 animate-spin" />
            Loading your account…
          </div>
        ) : (
          <>
            <p className="text-sm text-muted-foreground">
              Customer #{profile?.customer_number ?? "—"}
            </p>
            <h1 className="mt-1 font-display text-4xl font-semibold tracking-tight">
              Welcome, {profile?.full_name ?? "there"}.
            </h1>
            <p className="mt-2 text-muted-foreground">
              Your dashboard will live here. Phase 2 brings accounts, balances and
              transactions.
            </p>

            <Card className="mt-8 border-border/60 p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-xs uppercase tracking-wider text-muted-foreground">
                    KYC verification
                  </p>
                  <p className="mt-2 text-lg font-semibold capitalize">
                    {profile?.kyc_status ?? "unverified"}
                  </p>
                  <p className="mt-1 text-sm text-muted-foreground">
                    Verification unlocks transfers, cards and investments.
                  </p>
                </div>
                <Button>Start verification</Button>
              </div>
            </Card>
          </>
        )}
      </main>
    </div>
  );
}
