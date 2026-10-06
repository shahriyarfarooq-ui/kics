import React, { useState, useRef, useEffect } from 'react';
import { MessageCircle, X, Minus, Send, Bot } from 'lucide-react';
import { knowledgeBase, quickQuestions } from '../data/chatbotData';

const Chatbot = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isMinimized, setIsMinimized] = useState(false);
  const [messages, setMessages] = useState([
    {
      id: 1,
      text: "Hello! 👋 I'm the KICS Assistant. I can help you learn about our research, programs, events, and more. Feel free to ask me anything!",
      sender: 'bot'
    }
  ]);
  const [input, setInput] = useState('');
  const [isTyping, setIsTyping] = useState(false);
  const [showGreeting, setShowGreeting] = useState(true);
  const messagesEndRef = useRef(null);
  const inputRef = useRef(null);

  // Auto-scroll to bottom
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  // Focus input when chat opens or un-minimizes
  useEffect(() => {
    if (isOpen && !isMinimized) {
      setTimeout(() => inputRef.current?.focus(), 300);
    }
  }, [isOpen, isMinimized]);

  // Enhanced answer finder - matches multiple keywords and combines responses
  const findAnswer = (question) => {
    const normalized = question.toLowerCase().trim();
    const matches = [];

    // Check for exact or partial matches
    for (const [key, value] of Object.entries(knowledgeBase)) {
      if (key === 'default') continue;

      // Check if question contains key or key contains question
      if (normalized.includes(key) || key.includes(normalized)) {
        matches.push({ key, value, relevance: 2 }); // High relevance
      }
    }

    // Check for keyword matches
    const keywords = [
      'kics', 'research', 'ai', 'cybersecurity', 'smart cities', 'embedded',
      'event', 'news', 'workshop', 'training', 'achievement', 'publication',
      'mission', 'collaboration', 'partner', 'award', 'program', 'admission',
      'internship', 'contact', 'location', 'director', 'lab', 'facility',
      'startup', 'icosst', 'stats', 'about'
    ];

    for (const keyword of keywords) {
      if (normalized.includes(keyword)) {
        for (const [key, value] of Object.entries(knowledgeBase)) {
          if (key.includes(keyword) && key !== 'default' && !matches.find(m => m.key === key)) {
            matches.push({ key, value, relevance: 1 }); // Medium relevance
          }
        }
      }
    }

    // If multiple matches, combine the most relevant ones
    if (matches.length > 0) {
      // Sort by relevance
      matches.sort((a, b) => b.relevance - a.relevance);

      // If asking about general KICS info, combine multiple responses
      const generalQuestions = ['what', 'about', 'tell me'];
      const isGeneralQuestion = generalQuestions.some(q => normalized.includes(q));

      if (isGeneralQuestion && matches.length > 1) {
        // Combine top 2-3 relevant matches
        const topMatches = matches.slice(0, Math.min(3, matches.length));
        return topMatches.map(m => m.value).join('\n\n');
      }

      // Return the most relevant match
      return matches[0].value;
    }

    // Fallback to default
    return knowledgeBase.default;
  };

  const handleSend = () => {
    if (!input.trim()) return;

    setShowGreeting(false); // Hide greeting after first message
    const userMessage = { id: Date.now(), text: input, sender: 'user' };
    setMessages(prev => [...prev, userMessage]);
    setInput('');
    setIsTyping(true);

    // Simulate bot thinking
    setTimeout(() => {
      const response = findAnswer(input);
      const botMessage = {
        id: Date.now() + 1,
        text: response,
        sender: 'bot'
      };
      setMessages(prev => [...prev, botMessage]);
      setIsTyping(false);
    }, 600 + Math.random() * 400);
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter') handleSend();
  };

  const handleQuickQuestion = (question) => {
    setShowGreeting(false);
    setInput(question);
    setTimeout(() => {
      const userMessage = { id: Date.now(), text: question, sender: 'user' };
      setMessages(prev => [...prev, userMessage]);
      setIsTyping(true);

      setTimeout(() => {
        const response = findAnswer(question);
        const botMessage = {
          id: Date.now() + 1,
          text: response,
          sender: 'bot'
        };
        setMessages(prev => [...prev, botMessage]);
        setIsTyping(false);
      }, 600 + Math.random() * 400);
    }, 100);
  };

  const toggleMinimize = (e) => {
    e.stopPropagation();
    setIsMinimized(!isMinimized);
  };

  const handleClose = (e) => {
    e.stopPropagation();
    setIsOpen(false);
    setIsMinimized(false);
  };

  const handleHeaderClick = () => {
    if (isMinimized) {
      setIsMinimized(false);
    }
  };

  // Render message with bullet points
  const renderMessage = (text) => {
    const lines = text.split('\n');
    return lines.map((line, idx) => {
      const trimmed = line.trim();

      // Check if line is a bullet point
      if (trimmed.startsWith('•') || trimmed.startsWith('-') || trimmed.startsWith('*')) {
        const content = trimmed.substring(1).trim();
        return (
          <div key={idx} className="flex items-start gap-2 ml-2 my-1">
            <span className="text-purple-500 mt-1 flex-shrink-0">•</span>
            <span className="flex-1">{content}</span>
          </div>
        );
      }

      // Check if line is a heading (starts with **)
      if (trimmed.includes('**')) {
        const cleanText = trimmed.replace(/\*\*/g, '');
        return (
          <div key={idx} className="font-semibold mt-2 mb-1">
            {cleanText}
          </div>
        );
      }

      // Regular paragraph
      if (trimmed) {
        return (
          <p key={idx} className="my-1">
            {trimmed}
          </p>
        );
      }

      // Empty line for spacing
      return <div key={idx} className="h-2" />;
    });
  };

  return (
    <>
      {/* Overlay - closes chat when clicked */}
      {isOpen && !isMinimized && (
        <div
          className="fixed inset-0 bg-black/10 backdrop-blur-[2px] z-40"
          onClick={() => setIsOpen(false)}
          aria-hidden="true"
        />
      )}

      {/* Chat Toggle Button with Pulsing Animation */}
      {!isOpen && (
        <button
          onClick={() => setIsOpen(true)}
          className="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group animate-pulse-slow"
          aria-label="Open chat"
        >
          <MessageCircle className="w-7 h-7 group-hover:scale-110 transition-transform" />
        </button>
      )}

      {/* Chat Window */}
      {isOpen && (
        <div
          className={`fixed bottom-6 right-6 z-40 w-[90vw] sm:w-[380px] transition-all duration-300 ${
            isMinimized ? 'h-auto' : 'h-[70vh] sm:h-[520px] max-h-[600px]'
          }`}
          onClick={(e) => e.stopPropagation()}
        >
          <div
            className={`bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 dark:border-white/10 flex flex-col overflow-hidden ${
              isMinimized ? '' : 'h-full'
            }`}
          >
            {/* Header - Clickable when minimized */}
            <div
              className="flex-shrink-0 px-5 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white cursor-pointer"
              onClick={handleHeaderClick}
            >
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <Bot className="w-5 h-5" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-sm">KICS Assistant</h3>
                    <p className="text-xs text-white/80">Ask me anything about KICS</p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <button
                    onClick={toggleMinimize}
                    className="text-white/80 hover:text-white transition-colors p-1 hover:bg-white/10 rounded"
                    aria-label={isMinimized ? "Maximize" : "Minimize"}
                  >
                    <Minus className="w-4 h-4" />
                  </button>
                  <button
                    onClick={handleClose}
                    className="text-white/80 hover:text-white transition-colors p-1 hover:bg-white/10 rounded"
                    aria-label="Close chat"
                  >
                    <X className="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            {/* Chat Content - Hidden when minimized */}
            {!isMinimized && (
              <>
                {/* Messages */}
                <div className="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50/50 dark:bg-gray-800/30">
                  {showGreeting && (
                    <div className="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-4 rounded-xl border border-purple-200/50 dark:border-purple-500/30">
                      <h2 className="text-base font-semibold text-purple-900 dark:text-purple-200 mb-2">
                        Welcome to KICS! 🎓
                      </h2>
                      <p className="text-sm text-purple-800 dark:text-purple-300">
                        I can help you learn about our research areas, programs, events, achievements, and more. Try asking:
                      </p>
                      <div className="mt-3 space-y-1 text-xs text-purple-700 dark:text-purple-400">
                        <div>• What research areas does KICS focus on?</div>
                        <div>• Tell me about training programs</div>
                        <div>• What are the latest news?</div>
                      </div>
                    </div>
                  )}

                  {messages.map((msg) => (
                    <div
                      key={msg.id}
                      className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'}`}
                    >
                      <div
                        className={`max-w-[85%] px-4 py-2.5 rounded-2xl text-sm ${
                          msg.sender === 'user'
                            ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-br-sm'
                            : 'bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm text-gray-800 dark:text-gray-200 rounded-bl-sm shadow-sm border border-white/50 dark:border-gray-700/50'
                        }`}
                      >
                        {msg.sender === 'bot' ? renderMessage(msg.text) : msg.text}
                      </div>
                    </div>
                  ))}

                  {isTyping && (
                    <div className="flex justify-start">
                      <div className="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm px-4 py-2.5 rounded-2xl rounded-bl-sm shadow-sm border border-white/50 dark:border-gray-700/50">
                        <div className="flex gap-1">
                          <span className="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style={{ animationDelay: '0ms' }}></span>
                          <span className="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style={{ animationDelay: '150ms' }}></span>
                          <span className="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style={{ animationDelay: '300ms' }}></span>
                        </div>
                      </div>
                    </div>
                  )}
                  <div ref={messagesEndRef} />
                </div>

                {/* Quick Questions */}
                <div className="flex-shrink-0 px-4 py-2 border-t border-gray-200/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/30">
                  <div className="flex gap-2 overflow-x-auto pb-1 scrollbar-thin scrollbar-thumb-purple-400/30">
                    {quickQuestions.slice(0, 4).map((q, i) => (
                      <button
                        key={i}
                        onClick={() => handleQuickQuestion(q)}
                        className="flex-shrink-0 px-3 py-1.5 text-xs bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm
                                   rounded-full border border-purple-200/50 dark:border-purple-500/30
                                   text-purple-700 dark:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/30
                                   transition-all duration-200 hover:scale-105 whitespace-nowrap"
                      >
                        {q}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Input */}
                <div className="flex-shrink-0 p-3 border-t border-gray-200/50 dark:border-gray-700/50 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm">
                  <div className="flex gap-2">
                    <input
                      ref={inputRef}
                      type="text"
                      value={input}
                      onChange={(e) => setInput(e.target.value)}
                      onKeyPress={handleKeyPress}
                      placeholder="Ask a question..."
                      className="flex-1 px-4 py-2.5 text-sm bg-white/80 dark:bg-gray-800/80
                                 border border-gray-200/50 dark:border-gray-700/50
                                 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50
                                 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                    />
                    <button
                      onClick={handleSend}
                      disabled={!input.trim()}
                      className="px-4 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600
                                 text-white rounded-xl hover:shadow-lg transition-all duration-300
                                 disabled:opacity-50 disabled:cursor-not-allowed
                                 flex items-center justify-center"
                      aria-label="Send message"
                    >
                      <Send className="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </>
            )}
          </div>
        </div>
      )}

      {/* Styles */}
      <style>{`
        @keyframes pulse-slow {
          0%, 100% { opacity: 1; transform: scale(1); }
          50% { opacity: 0.9; transform: scale(1.05); }
        }
        .animate-pulse-slow {
          animation: pulse-slow 2s ease-in-out infinite;
        }
        .scrollbar-thin::-webkit-scrollbar {
          height: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
          background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
          background: rgba(168, 85, 247, 0.3);
          border-radius: 9999px;
        }
      `}</style>
    </>
  );
};

export default Chatbot;
