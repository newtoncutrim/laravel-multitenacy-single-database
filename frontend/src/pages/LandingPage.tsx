import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import {
  Building2, Calendar, ClipboardList, Heart, Shield, Users, Package,
  DollarSign, BarChart3, FileText, Globe, Plug, MessageSquare, CreditCard,
  Headphones, Stethoscope, Syringe, Hotel, ShoppingCart, Layers, Lock,
  TrendingUp, Database, PieChart, Download, Star, ChevronRight, Menu, X,
  Activity, Target, Zap, CheckCircle2, ArrowRight, Play, Mail, Phone,
  MapPin, Clock, Award, BriefcaseMedical, Check
} from "lucide-react";
import vetHero from "../assets/vet-hero.jpg";

const fadeUp = {
  hidden: { opacity: 0, y: 30 },
  visible: (i: number) => ({
    opacity: 1, y: 0,
    transition: { delay: i * 0.08, duration: 0.5, ease: [0.25, 0.1, 0.25, 1] as const }
  })
};

const LandingPage = () => {
  const [mobileMenu, setMobileMenu] = useState(false);

  return (
    <div className="min-h-screen bg-background text-foreground overflow-x-hidden">
      {/* Header */}
      <header className="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-xl border-b border-border">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
          <Link to="/" className="flex items-center gap-2.5">
            <div className="w-9 h-9 bg-primary rounded-xl flex items-center justify-center">
              <span className="text-primary-foreground font-bold text-lg">V</span>
            </div>
            <span className="text-xl font-bold tracking-tight">
              vet<span className="text-primary">Flow</span>
            </span>
          </Link>

          <nav className="hidden md:flex items-center gap-8 text-sm font-medium text-muted-foreground">
            <a href="#funcionalidades" className="hover:text-foreground transition-colors">Funcionalidades</a>
            <a href="#modulos" className="hover:text-foreground transition-colors">Módulos</a>
            <a href="#relatorios" className="hover:text-foreground transition-colors">Relatórios</a>
            <a href="#beneficios" className="hover:text-foreground transition-colors">Benefícios</a>
            <a href="#planos" className="hover:text-foreground transition-colors">Planos</a>
          </nav>

          <div className="hidden md:flex items-center gap-3">
            <Link to="/login" className="px-4 py-2 text-sm font-medium text-foreground hover:text-primary transition-colors">
              Entrar
            </Link>
            <Link to="/login?mode=register" className="px-5 py-2.5 text-sm font-semibold bg-primary text-primary-foreground rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-primary/20">
              Cadastrar plano
            </Link>
          </div>

          <button onClick={() => setMobileMenu(!mobileMenu)} className="md:hidden p-2">
            {mobileMenu ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
        </div>

        {mobileMenu && (
          <div className="md:hidden bg-card border-t border-border p-4 space-y-3">
            <a href="#funcionalidades" className="block py-2 text-sm font-medium" onClick={() => setMobileMenu(false)}>Funcionalidades</a>
            <a href="#modulos" className="block py-2 text-sm font-medium" onClick={() => setMobileMenu(false)}>Módulos</a>
            <a href="#relatorios" className="block py-2 text-sm font-medium" onClick={() => setMobileMenu(false)}>Relatórios</a>
            <a href="#beneficios" className="block py-2 text-sm font-medium" onClick={() => setMobileMenu(false)}>Benefícios</a>
            <a href="#planos" className="block py-2 text-sm font-medium" onClick={() => setMobileMenu(false)}>Planos</a>
            <div className="pt-2 border-t border-border flex flex-col gap-2">
              <Link to="/login" className="py-2.5 text-center text-sm font-medium border border-border rounded-xl">Entrar</Link>
              <Link to="/login?mode=register" className="py-2.5 text-center text-sm font-semibold bg-primary text-primary-foreground rounded-xl">Cadastrar plano</Link>
            </div>
          </div>
        )}
      </header>

      {/* Hero */}
      <section className="pt-32 pb-20 lg:pt-40 lg:pb-28">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <motion.div initial="hidden" animate="visible" variants={fadeUp} custom={0}>
              <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold mb-6">
                <Zap className="w-3.5 h-3.5" /> Plataforma SaaS para clínicas veterinárias
              </div>
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight mb-6">
                Gestão veterinária
                <span className="text-primary"> completa</span> em um só lugar
              </h1>
              <p className="text-lg text-muted-foreground max-w-xl mb-8 leading-relaxed">
                Prontuário, agenda, financeiro, estoque, relatórios e muito mais. 
                Centralize a operação de sua clínica com uma plataforma moderna, segura e escalável.
              </p>
              <div className="flex flex-wrap gap-3">
                <Link to="/login?mode=register" className="inline-flex items-center gap-2 px-7 py-3.5 bg-primary text-primary-foreground font-semibold rounded-xl hover:opacity-90 transition-opacity shadow-xl shadow-primary/25">
                  Teste grátis <ArrowRight className="w-4 h-4" />
                </Link>
                <a href="#funcionalidades" className="inline-flex items-center gap-2 px-7 py-3.5 border border-border bg-card font-semibold rounded-xl hover:bg-muted transition-colors">
                  <Play className="w-4 h-4" /> Ver demonstração
                </a>
              </div>
              <div className="mt-8 flex items-center gap-6 text-sm text-muted-foreground">
                <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-accent" /> Sem cartão</span>
                <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-accent" /> Multi-unidade</span>
                <span className="flex items-center gap-1.5"><CheckCircle2 className="w-4 h-4 text-accent" /> Suporte incluso</span>
              </div>
            </motion.div>

            <motion.div initial={{ opacity: 0, x: 40 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.7, delay: 0.2 }}>
              <div className="relative">
                <div className="absolute -inset-4 bg-gradient-to-br from-primary/20 to-accent/20 rounded-3xl blur-2xl" />
                <div className="relative bg-card rounded-2xl border border-border shadow-2xl p-6">
                  <img
                    src={vetHero}
                    alt="Veterinaria atendendo um cachorro na clinica"
                    className="mb-4 h-44 w-full rounded-xl object-cover"
                  />
                  {/* Mock Dashboard Preview */}
                  <div className="flex items-center gap-2 mb-4">
                    <div className="w-3 h-3 rounded-full bg-destructive/60" />
                    <div className="w-3 h-3 rounded-full bg-yellow-400/60" />
                    <div className="w-3 h-3 rounded-full bg-accent/60" />
                    <div className="flex-1" />
                    <span className="text-xs text-muted-foreground">vetFlow Dashboard</span>
                  </div>
                  <div className="grid grid-cols-3 gap-3 mb-4">
                    {[
                      { label: "Atendimentos", value: "147", icon: Activity, color: "text-primary" },
                      { label: "Receita mensal", value: "R$ 84.5k", icon: TrendingUp, color: "text-accent" },
                      { label: "Agendamentos", value: "32", icon: Calendar, color: "text-primary" },
                    ].map((m) => (
                      <div key={m.label} className="bg-muted/50 rounded-xl p-3">
                        <m.icon className={`w-4 h-4 ${m.color} mb-1`} />
                        <p className="text-lg font-bold">{m.value}</p>
                        <p className="text-xs text-muted-foreground">{m.label}</p>
                      </div>
                    ))}
                  </div>
                  <div className="bg-muted/30 rounded-xl p-4 h-32 flex items-end gap-1.5">
                    {[40, 65, 50, 80, 55, 90, 70, 85, 60, 95, 75, 88].map((h, i) => (
                      <div key={i} className="flex-1 bg-primary/20 rounded-t" style={{ height: `${h}%` }}>
                        <div className="w-full bg-primary rounded-t" style={{ height: `${Math.min(100, h + 15)}%` }} />
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Features */}
      <section id="funcionalidades" className="py-20 bg-card">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0} className="text-center mb-14">
            <span className="text-xs font-semibold uppercase tracking-widest text-primary">Funcionalidades</span>
            <h2 className="text-3xl sm:text-4xl font-extrabold mt-3 mb-4">Tudo que sua clínica precisa</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">Do atendimento ao financeiro, cada detalhe foi pensado para simplificar a rotina e elevar a produtividade.</p>
          </motion.div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {[
              { icon: Building2, title: "Gestão Multi-Clínica", desc: "Administre múltiplas unidades em uma única plataforma com dados isolados e seguros." },
              { icon: Calendar, title: "Agenda Veterinária", desc: "Calendário visual com disponibilidade em tempo real e filtros por profissional." },
              { icon: Stethoscope, title: "Prontuário Clínico", desc: "+40 fichas clínicas e laboratoriais com permissões granulares por exame." },
              { icon: Users, title: "CRM & Clientes", desc: "Cadastro completo de tutores com geolocalização, filtros avançados e mini cadastro." },
              { icon: Heart, title: "Gestão de Pets", desc: "Perfil completo do pet com histórico, anexos, imagens e marcações anatômicas." },
              { icon: Syringe, title: "Vacinação", desc: "Registro de vacinas, cálculo automático de próximas doses e lembretes." },
              { icon: Hotel, title: "Internação / Hospedagem", desc: "Check-in, check-out, acompanhamento diário vinculado ao faturamento." },
              { icon: DollarSign, title: "Financeiro Completo", desc: "Recebimentos, despesas, comissões, cobrança por e-mail e dashboards." },
              { icon: Package, title: "Estoque & Produtos", desc: "Controle de lote, validade, fornecedores e relatórios de posição." },
              { icon: ShoppingCart, title: "Serviços & Preços", desc: "Catálogo de serviços, múltiplas tabelas de preço e pacotes com desconto." },
              { icon: BarChart3, title: "Relatórios Gerenciais", desc: "Extrato, produção, comissões, estoque, desempenho e exportação Excel." },
              { icon: FileText, title: "Documentos & Templates", desc: "Atestados, laudos, receitas, contratos e envio por e-mail." },
              { icon: Plug, title: "Integrações", desc: "Omie, PagarMe, Nibo, Bitrix24, webhooks, ViaCEP e Google Maps." },
              { icon: Globe, title: "Agendamento Online", desc: "Website público com serviços, blog, eventos, adoção e veterinário a domicílio." },
              { icon: Lock, title: "Controle de Acesso", desc: "Perfis e permissões granulares para cada função e dado do sistema." },
            ].map((f, i) => (
              <motion.div key={f.title} initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={i}
                className="group bg-background rounded-2xl border border-border p-6 hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300">
                <div className="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                  <f.icon className="w-5 h-5 text-primary" />
                </div>
                <h3 className="font-bold text-lg mb-2">{f.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{f.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Benefits */}
      <section id="beneficios" className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-16 items-center">
            <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0}>
              <span className="text-xs font-semibold uppercase tracking-widest text-accent">Benefícios</span>
              <h2 className="text-3xl sm:text-4xl font-extrabold mt-3 mb-6">Por que escolher o vetFlow?</h2>
              <div className="space-y-5">
                {[
                  { icon: Target, title: "Mais organização", desc: "Centralização de dados elimina retrabalho e duplicidades." },
                  { icon: Zap, title: "Automação inteligente", desc: "Lembretes, cobranças e processos automatizados economizam horas." },
                  { icon: DollarSign, title: "Controle financeiro total", desc: "Receitas, despesas, comissões e extratos em um só lugar." },
                  { icon: ClipboardList, title: "Histórico clínico completo", desc: "Prontuário digital com +40 fichas e acesso seguro por permissão." },
                  { icon: BarChart3, title: "Decisões baseadas em dados", desc: "Relatórios detalhados e dashboards para gestão estratégica." },
                  { icon: Layers, title: "Escalável para multiunidades", desc: "Cresça sem trocar de sistema. Multi-tenancy com isolamento total." },
                  { icon: Award, title: "Confiança e segurança", desc: "Dados sensíveis isolados por conta com controle granular de acesso." },
                ].map((b, i) => (
                  <motion.div key={b.title} initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={i}
                    className="flex gap-4">
                    <div className="w-10 h-10 bg-accent/10 rounded-xl flex items-center justify-center flex-shrink-0">
                      <b.icon className="w-5 h-5 text-accent" />
                    </div>
                    <div>
                      <h3 className="font-bold mb-1">{b.title}</h3>
                      <p className="text-sm text-muted-foreground">{b.desc}</p>
                    </div>
                  </motion.div>
                ))}
              </div>
            </motion.div>

            <motion.div initial={{ opacity: 0, x: 40 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }}>
              <div className="bg-card rounded-2xl border border-border p-6 shadow-xl">
                <div className="flex items-center justify-between mb-6">
                  <h3 className="font-bold">Resultados com o vetFlow</h3>
                  <span className="text-xs text-muted-foreground">Últimos 30 dias</span>
                </div>
                <div className="grid grid-cols-2 gap-4 mb-6">
                  {[
                    { label: "Tempo economizado", value: "12h/sem", trend: "+34%" },
                    { label: "Atendimentos", value: "428", trend: "+18%" },
                    { label: "Receita", value: "R$ 127k", trend: "+22%" },
                    { label: "Satisfação", value: "4.9/5", trend: "+8%" },
                  ].map((s) => (
                    <div key={s.label} className="bg-muted/50 rounded-xl p-4">
                      <p className="text-2xl font-bold">{s.value}</p>
                      <p className="text-xs text-muted-foreground mt-1">{s.label}</p>
                      <span className="text-xs font-semibold text-accent">{s.trend}</span>
                    </div>
                  ))}
                </div>
                <div className="bg-muted/30 rounded-xl p-4">
                  <div className="flex items-end gap-2 h-24">
                    {[30, 45, 35, 60, 50, 75, 65, 80, 70, 90, 85, 95].map((h, i) => (
                      <div key={i} className="flex-1 rounded-t bg-gradient-to-t from-primary to-primary/60" style={{ height: `${h}%` }} />
                    ))}
                  </div>
                  <div className="flex justify-between mt-2 text-xs text-muted-foreground">
                    <span>Jan</span><span>Fev</span><span>Mar</span><span>Abr</span><span>Mai</span><span>Jun</span>
                    <span>Jul</span><span>Ago</span><span>Set</span><span>Out</span><span>Nov</span><span>Dez</span>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Reports & Dashboard */}
      <section id="relatorios" className="py-20 bg-card">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0} className="text-center mb-14">
            <span className="text-xs font-semibold uppercase tracking-widest text-primary">Relatórios & Dashboard</span>
            <h2 className="text-3xl sm:text-4xl font-extrabold mt-3 mb-4">Dados que impulsionam decisões</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">Visualize a saúde financeira e operacional da sua clínica com dashboards interativos e relatórios prontos para exportar.</p>
          </motion.div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {[
              { icon: DollarSign, title: "Extrato Financeiro", desc: "Receitas, despesas e saldo consolidado." },
              { icon: TrendingUp, title: "Produção", desc: "Volume de atendimentos e procedimentos." },
              { icon: Users, title: "Comissões", desc: "Cálculo automático por profissional." },
              { icon: Package, title: "Estoque", desc: "Posição, custo e validade dos produtos." },
              { icon: Heart, title: "Aniversariantes", desc: "Pets e tutores para ações de marketing." },
              { icon: Clock, title: "Atividades por Período", desc: "Análise temporal de operações." },
              { icon: Star, title: "Desempenho Profissional", desc: "Métricas individuais de cada veterinário." },
              { icon: Download, title: "Exportação Excel", desc: "Todos os relatórios em .xlsx." },
            ].map((r, i) => (
              <motion.div key={r.title} initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={i}
                className="bg-background rounded-xl border border-border p-5 hover:shadow-md transition-shadow">
                <r.icon className="w-5 h-5 text-primary mb-3" />
                <h3 className="font-bold text-sm mb-1">{r.title}</h3>
                <p className="text-xs text-muted-foreground">{r.desc}</p>
              </motion.div>
            ))}
          </div>

          {/* Dashboard mockup */}
          <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.6 }}
            className="bg-background rounded-2xl border border-border shadow-xl p-6">
            <div className="flex items-center gap-2 mb-4">
              <div className="w-3 h-3 rounded-full bg-destructive/60" />
              <div className="w-3 h-3 rounded-full bg-yellow-400/60" />
              <div className="w-3 h-3 rounded-full bg-accent/60" />
              <div className="flex-1" />
              <span className="text-xs text-muted-foreground">Dashboard — Relatórios</span>
            </div>
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
              {[
                { label: "Receita Total", value: "R$ 284.730", change: "+18.2%", up: true },
                { label: "Atendimentos", value: "1.847", change: "+12.5%", up: true },
                { label: "Ticket Médio", value: "R$ 154", change: "+5.3%", up: true },
                { label: "Novos Clientes", value: "186", change: "+24.1%", up: true },
              ].map((m) => (
                <div key={m.label} className="bg-card rounded-xl border border-border p-4">
                  <p className="text-xs text-muted-foreground mb-1">{m.label}</p>
                  <p className="text-xl font-bold">{m.value}</p>
                  <span className="text-xs font-semibold text-accent">{m.change}</span>
                </div>
              ))}
            </div>
            <div className="grid lg:grid-cols-3 gap-4">
              <div className="lg:col-span-2 bg-card rounded-xl border border-border p-4">
                <p className="text-sm font-bold mb-4">Receita vs Despesas</p>
                <div className="flex items-end gap-3 h-32">
                  {[
                    { r: 75, d: 45 }, { r: 85, d: 50 }, { r: 65, d: 40 },
                    { r: 90, d: 55 }, { r: 80, d: 48 }, { r: 95, d: 52 },
                  ].map((v, i) => (
                    <div key={i} className="flex-1 flex gap-1 items-end">
                      <div className="flex-1 bg-primary rounded-t" style={{ height: `${v.r}%` }} />
                      <div className="flex-1 bg-primary/25 rounded-t" style={{ height: `${v.d}%` }} />
                    </div>
                  ))}
                </div>
                <div className="flex gap-4 mt-3 text-xs text-muted-foreground">
                  <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 bg-primary rounded-sm" />Receita</span>
                  <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 bg-primary/25 rounded-sm" />Despesas</span>
                </div>
              </div>
              <div className="bg-card rounded-xl border border-border p-4">
                <p className="text-sm font-bold mb-4">Top Serviços</p>
                <div className="space-y-3">
                  {[
                    { name: "Consulta", pct: 85 },
                    { name: "Vacinação", pct: 62 },
                    { name: "Cirurgia", pct: 45 },
                    { name: "Exames", pct: 38 },
                    { name: "Banho & Tosa", pct: 30 },
                  ].map((s) => (
                    <div key={s.name}>
                      <div className="flex justify-between text-xs mb-1">
                        <span>{s.name}</span>
                        <span className="text-muted-foreground">{s.pct}%</span>
                      </div>
                      <div className="h-1.5 bg-muted rounded-full">
                        <div className="h-full bg-primary rounded-full" style={{ width: `${s.pct}%` }} />
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Modules */}
      <section id="modulos" className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0} className="text-center mb-14">
            <span className="text-xs font-semibold uppercase tracking-widest text-accent">Módulos</span>
            <h2 className="text-3xl sm:text-4xl font-extrabold mt-3 mb-4">Uma plataforma, dezenas de módulos</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">Cada módulo foi projetado para resolver uma necessidade real da rotina veterinária.</p>
          </motion.div>
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            {[
              { icon: Building2, label: "Administração" },
              { icon: Users, label: "CRM & Clientes" },
              { icon: Heart, label: "Pets" },
              { icon: Syringe, label: "Vacinação" },
              { icon: Calendar, label: "Agenda" },
              { icon: Stethoscope, label: "Prontuário" },
              { icon: Hotel, label: "Hotel / Internação" },
              { icon: Package, label: "Produtos & Estoque" },
              { icon: ShoppingCart, label: "Serviços" },
              { icon: DollarSign, label: "Financeiro" },
              { icon: BarChart3, label: "Relatórios" },
              { icon: FileText, label: "Documentos" },
              { icon: Globe, label: "Website Público" },
              { icon: Plug, label: "Integrações" },
              { icon: MessageSquare, label: "Comunicação" },
              { icon: CreditCard, label: "Faturamento SaaS" },
              { icon: Headphones, label: "Suporte & Operação" },
            ].map((m, i) => (
              <motion.div key={m.label} initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={i}
                className="group bg-card rounded-2xl border border-border p-5 text-center hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 cursor-default">
                <div className="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                  <m.icon className="w-6 h-6 text-primary" />
                </div>
                <p className="text-sm font-semibold">{m.label}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Pricing */}
      <section id="planos" className="py-20 bg-muted/30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0} className="text-center mb-14">
            <span className="text-xs font-semibold uppercase tracking-widest text-primary">Planos</span>
            <h2 className="text-3xl sm:text-4xl font-extrabold mt-3 mb-4">Escolha o plano ideal para sua clínica</h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">Preços transparentes, sem surpresas. Comece com um teste gratuito de 14 dias.</p>
          </motion.div>

          <div className="grid md:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto">
            {/* Start */}
            <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={1}
              className="bg-background rounded-2xl border border-border p-6 lg:p-8 flex flex-col">
              <div className="mb-6">
                <h3 className="text-xl font-bold mb-2">Start</h3>
                <p className="text-sm text-muted-foreground">Perfeito para iniciar</p>
              </div>
              <div className="mb-6">
                <span className="text-4xl font-extrabold">R$ 149</span>
                <span className="text-muted-foreground">/mês</span>
              </div>
              <ul className="space-y-3 mb-8 flex-1">
                {[
                  "1 unidade",
                  "Até 2 usuários",
                  "100 cadastros de clientes",
                  "Prontuário básico",
                  "Agenda completa",
                  "Financeiro básico",
                  "Suporte por email",
                ].map((feature) => (
                  <li key={feature} className="flex items-center gap-3 text-sm">
                    <Check className="w-4 h-4 text-accent flex-shrink-0" />
                    <span className="text-muted-foreground">{feature}</span>
                  </li>
                ))}
              </ul>
              <Link to="/login?mode=register" className="w-full py-3 text-center font-semibold border border-border rounded-xl hover:bg-muted transition-colors">
                Começar teste grátis
              </Link>
            </motion.div>

            {/* Clínica - Destaque */}
            <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={2}
              className="bg-background rounded-2xl border-2 border-primary p-6 lg:p-8 flex flex-col relative shadow-xl shadow-primary/10">
              <div className="absolute -top-4 left-1/2 -translate-x-1/2">
                <span className="px-4 py-1.5 bg-primary text-primary-foreground text-xs font-semibold rounded-full">
                  Mais popular
                </span>
              </div>
              <div className="mb-6 pt-2">
                <h3 className="text-xl font-bold mb-2">Clínica</h3>
                <p className="text-sm text-muted-foreground">Para clínicas em crescimento</p>
              </div>
              <div className="mb-6">
                <span className="text-4xl font-extrabold">R$ 249</span>
                <span className="text-muted-foreground">/mês</span>
              </div>
              <ul className="space-y-3 mb-8 flex-1">
                {[
                  "1 unidade",
                  "Até 10 usuários",
                  "Clientes ilimitados",
                  "Prontuário completo (+40 fichas)",
                  "Vacinação e lembretes",
                  "Financeiro avançado",
                  "Relatórios gerenciais",
                  "Agendamento online",
                  "Suporte prioritário",
                ].map((feature) => (
                  <li key={feature} className="flex items-center gap-3 text-sm">
                    <Check className="w-4 h-4 text-primary flex-shrink-0" />
                    <span className="text-muted-foreground">{feature}</span>
                  </li>
                ))}
              </ul>
              <Link to="/login?mode=register" className="w-full py-3 text-center font-semibold bg-primary text-primary-foreground rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-primary/20">
                Começar teste grátis
              </Link>
            </motion.div>

            {/* Multiunidade */}
            <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={3}
              className="bg-background rounded-2xl border border-border p-6 lg:p-8 flex flex-col">
              <div className="mb-6">
                <h3 className="text-xl font-bold mb-2">Multiunidade</h3>
                <p className="text-sm text-muted-foreground">Para redes e franquias</p>
              </div>
              <div className="mb-6">
                <span className="text-4xl font-extrabold">R$ 499</span>
                <span className="text-muted-foreground">/mês</span>
              </div>
              <ul className="space-y-3 mb-8 flex-1">
                {[
                  "Unidades ilimitadas",
                  "Usuários ilimitados",
                  "Tudo do plano Clínica",
                  "Gestão multi-unidade",
                  "Consolidação de relatórios",
                  "API e webhooks",
                  "Integrações avançadas",
                  "Suporte dedicado",
                  "Treinamento incluído",
                ].map((feature) => (
                  <li key={feature} className="flex items-center gap-3 text-sm">
                    <Check className="w-4 h-4 text-accent flex-shrink-0" />
                    <span className="text-muted-foreground">{feature}</span>
                  </li>
                ))}
              </ul>
              <Link to="/login" className="w-full py-3 text-center font-semibold border border-border rounded-xl hover:bg-muted transition-colors">
                Falar com especialista
              </Link>
            </motion.div>
          </div>

          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={4} className="text-center mt-12">
            <p className="text-sm text-muted-foreground flex items-center justify-center gap-6 flex-wrap">
              <span className="flex items-center gap-2"><Check className="w-4 h-4 text-accent" /> Sem contrato de fidelidade</span>
              <span className="flex items-center gap-2"><Check className="w-4 h-4 text-accent" /> 14 dias de teste grátis</span>
              <span className="flex items-center gap-2"><Check className="w-4 h-4 text-accent" /> Migração de dados gratuita</span>
            </p>
          </motion.div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <motion.div initial="hidden" whileInView="visible" viewport={{ once: true }} variants={fadeUp} custom={0}
            className="bg-gradient-to-br from-primary to-primary/80 rounded-3xl p-12 lg:p-16 text-primary-foreground relative overflow-hidden">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(255,255,255,0.1),transparent_60%)]" />
            <div className="relative z-10">
              <BriefcaseMedical className="w-12 h-12 mx-auto mb-6 opacity-80" />
              <h2 className="text-3xl sm:text-4xl font-extrabold mb-4">Comece a transformar a gestão da sua clínica</h2>
              <p className="text-lg opacity-90 mb-8 max-w-xl mx-auto">
                Junte-se a centenas de clínicas que já modernizaram sua operação com o vetFlow.
              </p>
              <div className="flex flex-wrap justify-center gap-4">
                <Link to="/login?mode=register" className="px-8 py-3.5 bg-card text-foreground font-semibold rounded-xl hover:opacity-90 transition-opacity shadow-lg">
                  Teste grátis
                </Link>
                <Link to="/login?mode=register" className="px-8 py-3.5 border-2 border-primary-foreground/30 text-primary-foreground font-semibold rounded-xl hover:bg-primary-foreground/10 transition-colors">
                  Escolha um plano
                </Link>
              </div>
              <p className="mt-6 text-sm opacity-70">ou fale com um especialista: contato@vetflow.com.br</p>
            </div>
          </motion.div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-foreground text-background/80 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <div>
              <div className="flex items-center gap-2.5 mb-4">
                <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                  <span className="text-primary-foreground font-bold">V</span>
                </div>
                <span className="text-lg font-bold text-background">
                  vet<span className="text-primary">Flow</span>
                </span>
              </div>
              <p className="text-sm opacity-60 leading-relaxed mb-4">
                Plataforma SaaS de gestão veterinária multiunidade. Prontuário, agenda, financeiro e muito mais.
              </p>
              <div className="flex gap-3">
                {[Globe, Heart, Plug, Mail].map((Icon, i) => (
                  <a key={i} href="#" className="w-9 h-9 rounded-lg bg-background/10 flex items-center justify-center hover:bg-background/20 transition-colors">
                    <Icon className="w-4 h-4" />
                  </a>
                ))}
              </div>
            </div>
            <div>
              <h3 className="text-sm font-bold text-background mb-4">Produto</h3>
              <ul className="space-y-2.5 text-sm opacity-60">
                <li><a href="#funcionalidades" className="hover:opacity-100 transition-opacity">Funcionalidades</a></li>
                <li><a href="#modulos" className="hover:opacity-100 transition-opacity">Módulos</a></li>
                <li><a href="#relatorios" className="hover:opacity-100 transition-opacity">Relatórios</a></li>
                <li><a href="#" className="hover:opacity-100 transition-opacity">Planos e Preços</a></li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-bold text-background mb-4">Empresa</h3>
              <ul className="space-y-2.5 text-sm opacity-60">
                <li><a href="#" className="hover:opacity-100 transition-opacity">Sobre nós</a></li>
                <li><a href="#" className="hover:opacity-100 transition-opacity">Blog</a></li>
                <li><a href="#" className="hover:opacity-100 transition-opacity">Carreiras</a></li>
                <li><a href="#" className="hover:opacity-100 transition-opacity">Contato</a></li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-bold text-background mb-4">Contato</h3>
              <ul className="space-y-2.5 text-sm opacity-60">
                <li className="flex items-center gap-2"><Mail className="w-4 h-4" /> contato@vetflow.com.br</li>
                <li className="flex items-center gap-2"><Phone className="w-4 h-4" /> (11) 99999-9999</li>
                <li className="flex items-center gap-2"><MapPin className="w-4 h-4" /> São Paulo, SP</li>
              </ul>
            </div>
          </div>
          <div className="border-t border-background/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs opacity-50">
            <p>© 2026 vetFlow. Todos os direitos reservados.</p>
            <div className="flex gap-6">
              <a href="#" className="hover:opacity-100 transition-opacity">Política de Privacidade</a>
              <a href="#" className="hover:opacity-100 transition-opacity">Termos de Uso</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;
