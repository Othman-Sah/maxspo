
import React from 'react';
import { 
  LineChart as RechartsLineChart, 
  Line, 
  AreaChart, 
  Area, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  Legend
} from 'recharts';
import { 
  TrendingUp, 
  TrendingDown, 
  DollarSign, 
  ArrowUpRight, 
  ArrowDownRight, 
  PieChart,
  Calendar,
  Filter,
  Download,
  Plus
} from 'lucide-react';
import { MOCK_REVENUE, MOCK_EXPENSES } from '../constants';
import { Expense } from '../types';

interface FinancialsViewProps {
  onAddExpense: () => void;
  onExport: () => void;
  onViewExpense: (expense: Expense) => void;
}

const FinancialsView: React.FC<FinancialsViewProps> = ({ onAddExpense, onExport, onViewExpense }) => {
  const totalEarnings = MOCK_REVENUE.reduce((acc, curr) => acc + curr.amount, 0);
  const totalExpenses = MOCK_REVENUE.reduce((acc, curr) => acc + curr.expenses, 0);
  const netProfit = totalEarnings - totalExpenses;

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
      <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <DollarSign className="text-emerald-600" size={32} />
            Analyse Financière
          </h1>
          <p className="text-slate-500 font-medium mt-1">Suivez la rentabilité de NEEDSPORT avec précision.</p>
        </div>
        <div className="flex items-center gap-3">
          <button 
            onClick={onExport}
            className="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm"
          >
            <Download size={18} /> Exporter Rapport
          </button>
          <button 
            onClick={onAddExpense}
            className="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100"
          >
            <Plus size={18} /> Nouvelle Dépense
          </button>
        </div>
      </div>

      {/* Summary Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4 text-emerald-100 opacity-20 group-hover:scale-110 transition-transform">
            <TrendingUp size={80} />
          </div>
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenus (An)</p>
          <h3 className="text-4xl font-black text-slate-900">{totalEarnings.toLocaleString('fr-FR')} DH</h3>
          <div className="mt-4 flex items-center gap-2 text-emerald-600 font-bold text-sm bg-emerald-50 w-fit px-3 py-1 rounded-full">
            <ArrowUpRight size={14} /> +12.4%
          </div>
        </div>

        <div className="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4 text-rose-100 opacity-20 group-hover:scale-110 transition-transform">
            <TrendingDown size={80} />
          </div>
          <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Dépenses (An)</p>
          <h3 className="text-4xl font-black text-slate-900">{totalExpenses.toLocaleString('fr-FR')} DH</h3>
          <div className="mt-4 flex items-center gap-2 text-rose-600 font-bold text-sm bg-rose-50 w-fit px-3 py-1 rounded-full">
            <ArrowUpRight size={14} /> +5.2%
          </div>
        </div>

        <div className="bg-slate-900 p-8 rounded-[32px] text-white shadow-xl shadow-slate-200 relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4 text-white/5 opacity-20 group-hover:scale-110 transition-transform">
            <PieChart size={80} />
          </div>
          <p className="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Bénéfice Net</p>
          <h3 className="text-4xl font-black text-white">{netProfit.toLocaleString('fr-FR')} DH</h3>
          <div className="mt-4 flex items-center gap-2 text-indigo-400 font-bold text-sm bg-white/10 w-fit px-3 py-1 rounded-full">
            Marge: {((netProfit / totalEarnings) * 100).toFixed(1)}%
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
          <div className="flex items-center justify-between mb-8">
            <h3 className="text-lg font-black text-slate-900">Revenus vs Dépenses</h3>
            <div className="flex gap-2">
              <button className="px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg">Mensuel</button>
              <button className="px-3 py-1.5 text-xs font-bold text-slate-400">Annuel</button>
            </div>
          </div>
          <div className="h-[350px] w-full">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={MOCK_REVENUE}>
                <defs>
                  <linearGradient id="colorEarnings" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor="#10b981" stopOpacity={0.1}/><stop offset="95%" stopColor="#10b981" stopOpacity={0}/></linearGradient>
                  <linearGradient id="colorExpenses" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor="#f43f5e" stopOpacity={0.1}/><stop offset="95%" stopColor="#f43f5e" stopOpacity={0}/></linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                <XAxis dataKey="month" axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12, fontWeight: 500}} dy={10} />
                <YAxis axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12, fontWeight: 500}} tickFormatter={(v) => `${v/1000}k`} />
                <Tooltip contentStyle={{borderRadius: '16px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'}} />
                <Legend iconType="circle" wrapperStyle={{paddingTop: '20px', fontSize: '12px', fontWeight: 600}} />
                <Area name="Revenus" type="monotone" dataKey="amount" stroke="#10b981" strokeWidth={3} fillOpacity={1} fill="url(#colorEarnings)" />
                <Area name="Dépenses" type="monotone" dataKey="expenses" stroke="#f43f5e" strokeWidth={3} fillOpacity={1} fill="url(#colorExpenses)" />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex flex-col">
          <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
            <Filter size={20} className="text-rose-500" />
            Dernières Dépenses
          </h3>
          <div className="flex-1 space-y-4 overflow-y-auto pr-2">
            {MOCK_EXPENSES.map((expense) => (
              <div 
                key={expense.id} 
                onClick={() => onViewExpense(expense)}
                className="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between group hover:border-rose-200 transition-all cursor-pointer"
              >
                <div className="flex items-center gap-3">
                  <div className={`h-10 w-10 rounded-xl flex items-center justify-center font-black text-xs ${expense.status === 'paye' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}`}>
                    {expense.category.charAt(0)}
                  </div>
                  <div>
                    <p className="text-sm font-bold text-slate-900">{expense.category}</p>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{expense.date}</p>
                  </div>
                </div>
                <div className="text-right">
                  <p className="text-sm font-black text-rose-600">-{expense.amount} DH</p>
                  <p className={`text-[10px] font-black uppercase ${expense.status === 'paye' ? 'text-emerald-500' : 'text-rose-400 animate-pulse'}`}>
                    {expense.status === 'paye' ? 'Réglé' : 'En attente'}
                  </p>
                </div>
              </div>
            ))}
          </div>
          <button className="w-full mt-6 py-3 bg-slate-50 text-slate-500 text-xs font-black rounded-xl hover:bg-slate-100 transition-colors uppercase tracking-widest">
            Voir tout l'historique
          </button>
        </div>
      </div>
    </div>
  );
};

export default FinancialsView;
