
import React from 'react';
import { ArrowUpRight, ArrowDownRight, LucideIcon } from 'lucide-react';

interface StatCardProps {
  title: string;
  value: string | number;
  trend: number;
  icon: LucideIcon;
  color: string;
  prefix?: string;
}

const StatCard: React.FC<StatCardProps> = ({ title, value, trend, icon: Icon, color, prefix }) => {
  const isPositive = trend >= 0;

  return (
    <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 group">
      <div className="flex justify-between items-start mb-4">
        <div className={`p-3 rounded-xl ${color} bg-opacity-10 transition-transform group-hover:scale-110`}>
          <Icon className={`${color.replace('bg-', 'text-')}`} size={24} />
        </div>
        <div className={`flex items-center gap-1 text-sm font-semibold px-2 py-1 rounded-full ${isPositive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}`}>
          {isPositive ? <ArrowUpRight size={14} /> : <ArrowDownRight size={14} />}
          {Math.abs(trend)}%
        </div>
      </div>
      <div>
        <p className="text-slate-500 text-sm font-medium">{title}</p>
        <h3 className="text-2xl font-bold text-slate-900 mt-1">
          {prefix}{value}
        </h3>
      </div>
    </div>
  );
};

export default StatCard;
