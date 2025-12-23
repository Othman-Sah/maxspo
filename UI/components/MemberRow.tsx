
import React from 'react';
import { Mail, Phone, Star } from 'lucide-react';
import { Member } from '../types';

interface MemberRowProps {
  member: Member;
  onRenew: (member: Member) => void;
  onContact: (member: Member, type: 'mail' | 'phone') => void;
}

const MemberRow: React.FC<MemberRowProps> = ({ member, onRenew, onContact }) => {
  const isExpiringSoon = (dateStr: string) => {
    const diff = new Date(dateStr).getTime() - new Date().getTime();
    return diff < 7 * 24 * 60 * 60 * 1000;
  };

  return (
    <tr className="hover:bg-slate-50 transition-colors group">
      <td className="px-6 py-4 whitespace-nowrap">
        <div className="flex items-center">
          <div className="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm overflow-hidden shrink-0">
            {member.photo ? <img src={member.photo} alt="" className="w-full h-full object-cover" /> : member.firstName[0]}
          </div>
          <div className="ml-3 min-w-0">
            <div className="text-sm font-semibold text-slate-900 flex items-center gap-2 truncate">
              {member.firstName} {member.lastName}
              {member.isLoyal && <Star size={12} className="fill-amber-400 text-amber-400 shrink-0" />}
            </div>
            <div className="text-xs text-slate-500 truncate">{member.email}</div>
          </div>
        </div>
      </td>
      <td className="px-6 py-4 whitespace-nowrap">
        <span className="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
          {member.sport}
        </span>
      </td>
      <td className="px-6 py-4 whitespace-nowrap">
        <div className="flex flex-col">
          <span className={`text-sm font-medium ${isExpiringSoon(member.expiryDate) ? 'text-rose-600' : 'text-slate-700'}`}>
            {new Date(member.expiryDate).toLocaleDateString('fr-FR')}
          </span>
          {isExpiringSoon(member.expiryDate) && (
            <span className="text-[10px] font-bold text-rose-500 uppercase">Attention</span>
          )}
        </div>
      </td>
      <td className="px-6 py-4 whitespace-nowrap">
        <div className="flex items-center gap-2">
          <button 
            onClick={() => onContact(member, 'mail')}
            className="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
          >
            <Mail size={16} />
          </button>
          <button 
            onClick={() => onContact(member, 'phone')}
            className="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
          >
            <Phone size={16} />
          </button>
        </div>
      </td>
      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <button 
          onClick={() => onRenew(member)}
          className="text-indigo-600 hover:text-indigo-900 font-semibold text-xs py-1.5 px-3 bg-indigo-50 rounded-lg transition-all active:scale-95"
        >
          Renouveler
        </button>
      </td>
    </tr>
  );
};

export default MemberRow;
